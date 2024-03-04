<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$collectorFactory = $objectManager->get(\MageSuite\NotificationDashboard\Api\Data\CollectorInterfaceFactory::class);
$collectorRepository = $objectManager->get(\MageSuite\NotificationDashboard\Api\CollectorRepositoryInterface::class);

$collector = $collectorFactory->create()
    ->setName(\MageSuite\ReindexAndCacheClearNotification\Service\Notifier::COLLECTOR_NAME)
    ->setIsEnabled(1)
    ->setSeverity(\MageSuite\NotificationDashboard\Model\Source\Severity::SEVERITY_NOTICE)
    ->setLimitOnDashboard(10)
    ->setAddAdminNotification(0)
    ->setVisibleOnDashboard(1)
    ->setIsStatic(0);
$collectorRepository->save($collector);
