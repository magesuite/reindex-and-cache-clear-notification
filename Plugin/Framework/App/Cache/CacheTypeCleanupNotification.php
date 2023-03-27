<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Plugin\Framework\App\Cache;

class CacheTypeCleanupNotification
{
    protected \MageSuite\ReindexAndCacheClearNotification\Service\CacheNotifier $notifier;

    public function __construct(\MageSuite\ReindexAndCacheClearNotification\Service\CacheNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function afterCleanType(\Magento\Framework\App\Cache\TypeListInterface $subject, $result, $typeCode)
    {
        $this->notifier->notifyAboutSpecificCacheCleanup('type', $typeCode);
        return $result;
    }
}
