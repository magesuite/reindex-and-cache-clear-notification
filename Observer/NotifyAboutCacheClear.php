<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Observer;

class NotifyAboutCacheClear implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\ReindexAndCacheClearNotification\Service\CacheNotifier $notifier;

    public function __construct(\MageSuite\ReindexAndCacheClearNotification\Service\CacheNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        $this->notifier->notifyAboutCacheCleanup('Cache cleared', 'The cache has been cleared');
    }
}
