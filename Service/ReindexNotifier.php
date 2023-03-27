<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Service;

class ReindexNotifier extends Notifier
{
    public function notifyAboutReindex(string $indexTitle): void
    {
        $config = $this->configuration->getReindexConfig();
        if ($this->validateNotification($config)) {
            $this->notify(
                __('An index has been reindexed')->render(),
                sprintf(__('Index %s just has been reindexed')->render(), $indexTitle)
            );
        }
    }
}
