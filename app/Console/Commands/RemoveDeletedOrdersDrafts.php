<?php

namespace App\Console\Commands;

use App\Services\Orders\OrderDraftInstanceService;
use App\Services\Orders\OrdersDraftsCollectionService;
use Illuminate\Console\Command;

class RemoveDeletedOrdersDrafts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders_drafts:remove_deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        private OrdersDraftsCollectionService $ordersDraftsCollectionService,
        private OrderDraftInstanceService $orderDraftInstanceService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->output->title('Удаление черновиков заказов');

        $attributes = ['orders.*'];
        $params = [
            'filter' => [
                'only_trashed' => true,
            ],
        ];

        $ordersDrafts = $this->ordersDraftsCollectionService->getInstances(
            attributes: $attributes,
            requestParams: $params
        );

        $this->output->progressStart(count($ordersDrafts));

        foreach ($ordersDrafts as $orderDraft) {
            $this->orderDraftInstanceService->forceDeleteInstance($orderDraft['id']);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }
}
