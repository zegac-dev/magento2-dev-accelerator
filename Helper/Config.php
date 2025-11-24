<?php

namespace ZegacLabs\DevAccelerator\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

readonly class Config
{
    public function __construct(
        private ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Check if the module is enabled
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(
            'devaccelerator/general/module_enabled',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if the template debugger feature is enabled
     * @return bool
     */
    public function isTemplateDebuggerEnabled(): bool
    {
        if (!$this->isModuleEnabled()) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(
            'devaccelerator/general/template_comments',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if the container debugger feature is enabled
     * @return bool
     */
    public function isContainerDebuggerEnabled(): bool
    {
        $isModuleEnabled = (bool) $this->scopeConfig->getValue(
            'devaccelerator/general/module_enabled',
            ScopeInterface::SCOPE_STORE
        );

        if (!$isModuleEnabled) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(
            'devaccelerator/general/container_comments',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if the layout handles logger feature is enabled
     * @return bool
     */
    public function isLayoutHandlesLoggerEnabled(): bool
    {
        if (!$this->isModuleEnabled()) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(
            'devaccelerator/general/layout_handles_log',
            ScopeInterface::SCOPE_STORE
        );
    }
}
