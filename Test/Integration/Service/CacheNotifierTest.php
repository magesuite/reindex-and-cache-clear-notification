<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Test\Integration\Service;

class CacheNotifierTest extends \PHPUnit\Framework\TestCase
{
    protected const EXPECTED_NOTIFY_TITLE = 'The cache has just been cleared';
    protected const EXPECTED_NOTIFY_DESCRIPTION = 'The cache of tag cat_c has just been cleared';

    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\MageSuite\NotificationDashboard\Api\NotificationRepositoryInterface $notificationRepository;
    protected ?\MageSuite\ReindexAndCacheClearNotification\Service\CacheNotifier $notifier;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->notifier = $this
            ->getMockBuilder(\MageSuite\ReindexAndCacheClearNotification\Service\CacheNotifier::class)
            ->onlyMethods(['getCurrentTime'])
            ->setConstructorArgs([
                'configuration' => $this->objectManager->create(
                    \MageSuite\ReindexAndCacheClearNotification\Helper\Configuration::class
                ),
                'timezone' => $this->objectManager->create(\Magento\Framework\Stdlib\DateTime\TimezoneInterface::class),
                'addNotification' => $this->objectManager->create(\MageSuite\NotificationDashboard\Model\Command\Notification\AddNotification::class),
                'collectorRepository' => $this->objectManager->create(\MageSuite\NotificationDashboard\Api\CollectorRepositoryInterface::class),
                'cacheToWatch' => [
                    'type' => [],
                    'tag' => [
                        'cat_c'
                    ]
                ]
            ])
            ->getMock();

        $this->notificationRepository = $this->objectManager->get(\MageSuite\NotificationDashboard\Api\NotificationRepositoryInterface::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoConfigFixture default/system/cache_cleanup_debugger/is_notification_enabled 1
     * @magentoConfigFixture default/system/cache_cleanup_debugger/opening_time 07,00,00
     * @magentoConfigFixture default/system/cache_cleanup_debugger/closing_time 23,00,00
     * @magentoDataFixture MageSuite_ReindexAndCacheClearNotification::Test/Integration/_files/collector.php
     */
    public function testItSendsNotificationCorrectly(): void
    {
        $simulatedTime = new \DateTime('01.01.2022 10:00');
        $this->notifier->method('getCurrentTime')->willReturn($simulatedTime);

        $this->notifier->notifyAboutSpecificCacheCleanup('tag', 'cat_C');
        $this->notifier->notifyAboutSpecificCacheCleanup('tag', 'CAT_P');

        $notifications = $this->notificationRepository->getList()->getItems();
        $notification = array_pop($notifications);

        $this->assertEquals($notification->getTitle(), self::EXPECTED_NOTIFY_TITLE);
        $this->assertEquals($notification->getMessage(), self::EXPECTED_NOTIFY_DESCRIPTION);
    }
}
