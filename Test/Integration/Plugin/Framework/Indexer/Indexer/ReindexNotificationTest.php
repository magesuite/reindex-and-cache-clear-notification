<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Test\Integration\Plugin\Framework\Indexer\Indexer;

class ReindexNotificationTest extends \PHPUnit\Framework\TestCase
{
    protected const INDEXES_TO_REINDEX = ['design_config_grid', 'customer_grid'];

    protected ?\Magento\TestFramework\ObjectManager $objectManager;

    /**
     * @var \MageSuite\ReindexAndCacheClearNotification\Plugin\Framework\Indexer\Indexer\ReindexNotification|mixed
     */
    protected $plugin;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->plugin = $this
            ->getMockBuilder(
                \MageSuite\ReindexAndCacheClearNotification\Plugin\Framework\Indexer\Indexer\ReindexNotification::class
            )
            ->disableOriginalConstructor()
            ->getMock();
        $this->objectManager->addSharedInstance(
            $this->plugin,
            \MageSuite\ReindexAndCacheClearNotification\Plugin\Framework\Indexer\Indexer\ReindexNotification::class
        );
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testIfReindexTriggersThePlugin(): void
    {
        $this->plugin
            ->expects($this->exactly(2))
            ->method('afterReindexAll');
        $reindex = $this->objectManager->create(\MagePal\Reindex\Model\Reindex::class);
        $reindex->reindex(self::INDEXES_TO_REINDEX);
    }
}
