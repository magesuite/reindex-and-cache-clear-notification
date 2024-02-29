<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Service;

class CacheNotifier extends Notifier
{
    protected array $cacheToWatch;

    public function __construct(
        \MageSuite\ReindexAndCacheClearNotification\Helper\Configuration $configuration,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \MageSuite\NotificationDashboard\Model\Command\Notification\AddNotification $addNotification,
        \MageSuite\NotificationDashboard\Api\CollectorRepositoryInterface $collectorRepository,
        $cacheToWatch
    ) {
        parent::__construct($configuration, $timezone, $addNotification, $collectorRepository);
        $this->cacheToWatch = $cacheToWatch;
    }

    public function notifyAboutSpecificCacheCleanup(string $criteria, string $code): void
    {
        $code = strtolower($code);

        if (!in_array($code, $this->cacheToWatch[$criteria])) {
            return;
        }

        $config = $this->configuration->getCacheConfig();

        if (!$config->getIsNotificationEnabled()) {
            return;
        }

        if ($this->eventOccurredDuringTheGivenTime($this->getCurrentTime(), $config) || $code == "empty_tags") {
            $this->notify(
                __('The cache has just been cleared')->render(),
                sprintf(__('The cache of %s %s has just been cleared')->render(), $criteria, $code)
            );
        }
    }

    public function notifyAboutCacheCleanup(string $title, string $description): void
    {
        $config = $this->configuration->getCacheConfig();
        if ($this->validateNotification($config)) {
            $this->notify(
                __($title)->render(),
                __($description)->render()
            );
        }
    }
}
