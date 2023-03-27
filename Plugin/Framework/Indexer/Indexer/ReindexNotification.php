<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Plugin\Framework\Indexer\Indexer;

class ReindexNotification
{
    protected \MageSuite\ReindexAndCacheClearNotification\Service\ReindexNotifier $notifier;

    public function __construct(\MageSuite\ReindexAndCacheClearNotification\Service\ReindexNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function afterReindexAll(\Magento\Framework\Indexer\IndexerInterface $subject): void
    {
        $this->notifier->notifyAboutReindex($subject->getTitle());
    }
}
