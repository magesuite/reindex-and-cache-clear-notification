<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Test\Integration\Observer;

class NotifyAboutCacheClearTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;

    /**
     * @var \MageSuite\ReindexAndCacheClearNotification\Observer\NotifyAboutCacheClear
     */
    protected $observer;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->observer = $this
            ->getMockBuilder(
                \MageSuite\ReindexAndCacheClearNotification\Observer\NotifyAboutCacheClear::class
            )
            ->disableOriginalConstructor()
            ->getMock();
        $this->objectManager->addSharedInstance(
            $this->observer,
            \MageSuite\ReindexAndCacheClearNotification\Observer\NotifyAboutCacheClear::class
        );
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture MageSuite_ReindexAndCacheClearNotification::Test/Integration/_files/collector.php
     */
    public function testIfCacheClearTriggersObserver(): void
    {
        $this->observer
            ->expects($this->once())
            ->method('execute');
        $eventManager = $this->objectManager->get(\Magento\Framework\Event\ManagerInterface::class);
        $eventManager->dispatch('adminhtml_cache_flush_all');
    }
}
