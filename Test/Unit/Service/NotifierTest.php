<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Test\Unit\Service;

class NotifierTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\Magento\Framework\DataObjectFactory $dataObjectFactory;

    /**
     * @var \MageSuite\ReindexAndCacheClearNotification\Service\Notifier
     */
    protected $notifier;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->dataObjectFactory = $this->objectManager->create(
            \Magento\Framework\DataObjectFactory::class
        );
        $this->notifier = $this->objectManager->create(
            \MageSuite\ReindexAndCacheClearNotification\Service\Notifier::class
        );
    }

    public function testTimeValidation()
    {
        $simulatedTime = new \DateTime('14.08.2022 10:17 AM');

        $simulatedConfig = $this->dataObjectFactory->create()->setData([
            'opening_time' => '07,00,00',
            'closing_time' => '23,00,00',
        ]);
        $this->assertTrue($this->notifier->eventOccurredDuringTheGivenTime($simulatedTime, $simulatedConfig));

        $simulatedConfig = $this->dataObjectFactory->create()->setData([
            'opening_time' => '10,00,00',
            'closing_time' => '23,00,00',
        ]);
        $this->assertTrue($this->notifier->eventOccurredDuringTheGivenTime($simulatedTime, $simulatedConfig));

        $simulatedConfig = $this->dataObjectFactory->create()->setData([
            'opening_time' => '09,59,59',
            'closing_time' => '17,00,00',
        ]);
        $this->assertTrue($this->notifier->eventOccurredDuringTheGivenTime($simulatedTime, $simulatedConfig));

        $simulatedConfig = $this->dataObjectFactory->create()->setData([
            'opening_time' => '06,00,00',
            'closing_time' => '10,00,00',
        ]);
        $this->assertFalse($this->notifier->eventOccurredDuringTheGivenTime($simulatedTime, $simulatedConfig));
    }
}
