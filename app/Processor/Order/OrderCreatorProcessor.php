<?php

declare(strict_types=1);

namespace App\Processor\Order;

use App\DTOs\Order\Composite\OrderComposite;
use App\DTOs\Order\Composite\OrderCompositeInterface;
use App\DTOs\Order\OrderDTO;
use App\Events\Order\OrderEntityEvent;
use App\Events\Order\OrderEntityEventTypeEnum;
use App\Exceptions\NotSuitableSellerException;
use App\Exceptions\OrderException;
use App\Managers\OrderComposite\OrderCompositeManagerInterface;
use App\Models\Order\OrderStatus;
use App\Repositories\Order\OrderDraftRepositoryInterface;
use App\Services\Order\Checker\OrderCreationRestrictionByManufacturerCheckerInterface;
use App\Services\Order\Checker\OrderSellerCheckerInterface;
use App\Services\Order\OrderNumber\OrderNumberGeneratorServiceInterface;
use App\Services\Order\Processor\OrderInspectorProcessorInterface;
use Illuminate\Support\Carbon;
use function __;
use function auth;

final class OrderCreatorProcessor implements OrderCreatorProcessorInterface
{
    public function __construct(
        private readonly OrderCompositeManagerInterface $manager,
        private readonly OrderDraftRepositoryInterface $draftRepository,
        private readonly OrderSellerCheckerInterface $orderSellerChecker,
        private readonly OrderCreationRestrictionByManufacturerCheckerInterface $orderCreationRestrictionChecker,
        private readonly OrderNumberGeneratorServiceInterface $numberGeneratorService,
        private readonly OrderInspectorProcessorInterface $orderInspectorProcessor,
    )
    {
    }

    public function process(array $request): OrderCompositeInterface
    {
        $request['draft'] = false;

        if (!isset($request['user_id'])) {
            $request['user_id'] = auth('sanctum')->id();
        }

        if (!isset($requst['inspector_id'])) {
            $request['inspector_id'] = $this->orderInspectorProcessor->process();
        }

        $request['status'] = OrderStatus::STATUS_CREATED;

        $dto = new OrderDTO($request);

        if (!$this->orderSellerChecker->check($dto->main()['seller_id'])) {
            throw new NotSuitableSellerException(message: __('order.not_suitable_seller'));
        }

        if (!$this->orderCreationRestrictionChecker->check(
            $dto->main()['manufacturer_id'] ?? null,
            $dto->main()['order_date'] ?? null
        )) {
            throw new OrderException(message: __('order.limited_date'));
        }

        $orderComposite = new OrderComposite($dto);

        if (empty($request['number'])) {
            $orderComposite->order()->number = $this->numberGeneratorService->generate(
                $orderComposite->order()->order_date ?? Carbon::now()->startOfDay()->format('Y-m-d')
            );
        }

        if (!isset($dto->main()['draft_id'])) {
            $this->manager->create($orderComposite);

            return $orderComposite;
        }

        $draft = $this->draftRepository->find($dto->main()['draft_id']);

        if (!$draft) {
            $this->manager->create($orderComposite);

            return $orderComposite;
        }

        $draft->fill($orderComposite->order()->toArray());

        $draft->draft = false;

        $orderComposite->setOrder($draft);

        $this->manager->update($orderComposite);

        OrderEntityEvent::dispatch($orderComposite->order(), OrderEntityEventTypeEnum::CREATED);

        return $orderComposite;
    }
}