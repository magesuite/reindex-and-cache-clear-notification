<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Service;

class Notifier
{
    public const COLLECTOR_NAME = 'Reindex or cache clear';

    protected \MageSuite\ReindexAndCacheClearNotification\Helper\Configuration $configuration;
    protected \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone;
    protected \MageSuite\NotificationDashboard\Model\Command\Notification\AddNotification $addNotification;
    protected \MageSuite\NotificationDashboard\Api\CollectorRepositoryInterface $collectorRepository;

    public function __construct(
        \MageSuite\ReindexAndCacheClearNotification\Helper\Configuration $configuration,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \MageSuite\NotificationDashboard\Model\Command\Notification\AddNotification $addNotification,
        \MageSuite\NotificationDashboard\Api\CollectorRepositoryInterface $collectorRepository
    ) {
        $this->configuration = $configuration;
        $this->timezone = $timezone;
        $this->addNotification = $addNotification;
        $this->collectorRepository = $collectorRepository;
    }

    public function notify(string $notifyTitle, string $notifyDescription): void
    {
        $collector = $this->collectorRepository->get(self::COLLECTOR_NAME);
        $this->addNotification->execute(
            __($notifyDescription),
            $collector->getId(),
            $collector->getSeverity(),
            __($notifyTitle)
        );
    }

    public function eventOccurredDuringTheGivenTime(\DateTime $timeOfEvent, \Magento\Framework\DataObject $alertConfig): bool
    {
        $timeOfEvent = $timeOfEvent->format('H:i:s');
        $openingTime = str_replace(',', ':', $alertConfig->getOpeningTime());
        $closingTime = str_replace(',', ':', $alertConfig->getClosingTime());
        return ($timeOfEvent >= $openingTime && $timeOfEvent <= $closingTime);
    }

    public function getCurrentTime(): \DateTime
    {
        return $this->timezone->date();
    }

    public function validateNotification(\Magento\Framework\DataObject $config): bool
    {
        return $config->getIsNotificationEnabled() && $this->eventOccurredDuringTheGivenTime($this->getCurrentTime(), $config);
    }
}
