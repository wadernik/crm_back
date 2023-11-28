<?php

declare(strict_types=1);

namespace App\Console\Commands\Order;

use App\Managers\Order\Draft\OrderDraftManagerInterface;
use App\Repositories\Order\OrderDraftRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use function App\Helpers\Functions\load_service;
use function count;

final class ClearDeletedOrderDraftsCommand extends Command
{
    private readonly OrderDraftRepositoryInterface $repository;
    private readonly OrderDraftManagerInterface $manager;

    protected $signature = 'order:drafts:clear';

    protected $description = 'Remove deleted or leftover order drafts';

    public function __construct()
    {
        $this->repository = load_service(OrderDraftRepositoryInterface::class);
        $this->manager = load_service(OrderDraftManagerInterface::class);

        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Removing deleted order drafts');

        $removedDrafts = $this->repository->findAllBy(['filter' => ['only_trashed' => true]]);

        $filterDate = Carbon::now()->subMonth()->startOfDay();

        $leftoverDrafts = $this->repository->findAllBy(
            ['filter' => ['created_at_end' => $filterDate->format('y-m-d H:i:s')]]
        );

        if ($removedDrafts->isEmpty() && $leftoverDrafts->isEmpty()) {
            $this->info('No deleted or leftover drafts found');

            return;
        }

        $progressBar = $this->output->createProgressBar(count($removedDrafts));

        foreach ($removedDrafts->merge($leftoverDrafts) as $draft) {
            $this->manager->remove($draft);

            $progressBar->advance();
        }

        $this->newLine();

        $progressBar->finish();
    }
}