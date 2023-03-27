<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Test\Integration\Observer;

class NotifyAboutCacheFlushTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;

    /**
     * @var \MageSuite\ReindexAndCacheClearNotification\Observer\NotifyAboutCacheFlush
     */
    protected $observer;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->observer = $this
            ->getMockBuilder(
                \MageSuite\ReindexAndCacheClearNotification\Observer\NotifyAboutCacheFlush::class
            )
            ->disableOriginalConstructor()
            ->getMock();
        $this->objectManager->addSharedInstance(
            $this->observer,
            \MageSuite\ReindexAndCacheClearNotification\Observer\NotifyAboutCacheFlush::class
        );
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testIfCacheFlushTriggersObserver(): void
    {
        $this->observer
            ->expects($this->once())
            ->method('execute');
        $eventManager = $this->objectManager->get(\Magento\Framework\Event\ManagerInterface::class);
        $eventManager->dispatch('adminhtml_cache_flush_system');
    }
}
