<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Test\Integration\Service;

class ReindexNotifierTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\MageSuite\ReindexAndCacheClearNotification\Service\ReindexNotifier $notifier;
    protected ?\MageSuite\ReindexAndCacheClearNotification\Helper\Configuration $configuration;
    protected ?\Magento\Framework\DataObjectFactory $dataObjectFactory;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->dataObjectFactory = $this->objectManager->create(\Magento\Framework\DataObjectFactory::class);

        $this->configuration = $this->createStub(
            \MageSuite\ReindexAndCacheClearNotification\Helper\Configuration::class
        );
        $this->objectManager->addSharedInstance(
            $this->configuration,
            \MageSuite\ReindexAndCacheClearNotification\Helper\Configuration::class
        );

        $this->notifier = $this
            ->getMockBuilder(\MageSuite\ReindexAndCacheClearNotification\Service\ReindexNotifier::class)
            ->onlyMethods(['getCurrentTime', 'notify'])
            ->setConstructorArgs([
                'configuration' => $this->configuration,
                'timezone' => $this->objectManager->create(\Magento\Framework\Stdlib\DateTime\TimezoneInterface::class),
                'addNotification' => $this->objectManager->create(\MageSuite\NotificationDashboard\Model\Command\Notification\AddNotification::class),
                'collectorRepository' => $this->objectManager->create(\MageSuite\NotificationDashboard\Api\CollectorRepositoryInterface::class),
            ])
            ->getMock();
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_ReindexAndCacheClearNotification::Test/Integration/_files/collector.php
     */
    public function testItSendsNotificationCorrectly(): void
    {
        $simulatedTime = new \DateTime('01.01.2022 10:00 AM');
        $simulatedConfig = $this->dataObjectFactory->create()->setData([
            'is_notification_enabled' => 1,
            'opening_time' => '09,00,00',
            'closing_time' => '22,00,00',
        ]);

        $this->configuration->method('getReindexConfig')->willReturn($simulatedConfig);
        $this->notifier->method('getCurrentTime')->willReturn($simulatedTime);
        $this->notifier->expects($this->once())->method('notify');

        $this->notifier->notifyAboutReindex('Design Config Grid');
    }
}
