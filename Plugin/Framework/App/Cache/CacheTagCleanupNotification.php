<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Plugin\Framework\App\Cache;

class CacheTagCleanupNotification
{
    protected \MageSuite\ReindexAndCacheClearNotification\Service\CacheNotifier $notifier;

    public function __construct(\MageSuite\ReindexAndCacheClearNotification\Service\CacheNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function afterClean(\Magento\Framework\App\Cache $subject, $result, $tags = [])
    {
        if (!is_array($tags)) {
            $tags = [$tags];
        }

        if (empty($tags)) {
            $this->notifier->notifyAboutSpecificCacheCleanup('tag', 'empty_tags');
            return $result;
        }

        foreach ($tags as $tag) {
            if (empty($tag)) {
                $tag = 'empty_tags';
            }
            $this->notifier->notifyAboutSpecificCacheCleanup('tag', $tag);
        }
        return $result;
    }
}
