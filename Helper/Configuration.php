<?php

declare(strict_types=1);

namespace MageSuite\ReindexAndCacheClearNotification\Helper;

class Configuration
{
    protected const XML_PATH_CACHE_CLEANUP_NOTIFICATIONS_CONFIGURATION = 'system/cache_cleanup_debugger';
    protected const XML_PATH_REINDEX_CLEANUP_NOTIFICATIONS_CONFIGURATION = 'system/index_invalidation_debugger';

    protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig;
    protected ?\Magento\Framework\DataObject $config = null;
    protected \Magento\Framework\DataObjectFactory $dataObjectFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
        \Magento\Framework\DataObjectFactory $dataObjectFactory
    ) {
        $this->scopeConfig = $scopeConfigInterface;
        $this->dataObjectFactory = $dataObjectFactory;
    }

    protected function getConfig(string $xmlPath): \Magento\Framework\DataObject
    {
        $config = (array)$this->scopeConfig->getValue($xmlPath);
        return $this->dataObjectFactory->create()->setData($config);
    }

    public function getReindexConfig(): \Magento\Framework\DataObject
    {
        return $this->getConfig(self::XML_PATH_REINDEX_CLEANUP_NOTIFICATIONS_CONFIGURATION);
    }

    public function getCacheConfig(): \Magento\Framework\DataObject
    {
        return $this->getConfig(self::XML_PATH_CACHE_CLEANUP_NOTIFICATIONS_CONFIGURATION);
    }
}
