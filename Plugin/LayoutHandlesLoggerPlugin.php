<?php

namespace ZegacLabs\DevAccelerator\Plugin;

use Magento\Framework\App\State;
use Magento\Framework\View\Layout;
use ZegacLabs\DevAccelerator\Helper\Config;

class LayoutHandlesLoggerPlugin
{
    private ?bool $isEnabledCache = null;

    public function __construct(
        private readonly Config $config,
        private readonly State $appState
    ) {
    }

    public function afterGetOutput(Layout $subject, mixed $result): mixed
    {
        if (!$this->isEnabled() || !is_string($result)) {
            return $result;
        }

        $handles = $subject->getUpdate()->getHandles();

        if (empty($handles)) {
            return $result;
        }

        return $this->injectScript($result, $handles);
    }

    private function isEnabled(): bool
    {
        if ($this->isEnabledCache !== null) {
            return $this->isEnabledCache;
        }

        if ($this->appState->getMode() !== State::MODE_DEVELOPER) {
            return $this->isEnabledCache = false;
        }

        return $this->isEnabledCache = $this->config->isLayoutHandlesLoggerEnabled();
    }

    private function injectScript(string $html, array $handles): string
    {
        $script = '<script>' . $this->getScriptContent($handles) . '</script>';

        if (strpos($html, '</body>') !== false) {
            return str_replace('</body>', $script . '</body>', $html);
        }

        // If no body tag, append at the end
        return $html . $script;
    }

    private function getScriptContent(array $handles): string
    {
        $handlesJson = json_encode($handles);

        return <<<JS
                (function() {
                    var handles = $handlesJson;
                    if (handles && handles.length > 0) {
                        var headerStyle = 'color: #fff; background: #0066cc; padding: 4px 8px; border-radius: 3px; font-weight: bold; font-size: 13px;';
                        var handleStyle = 'color: #0066cc; font-weight: 500; padding-left: 4px;';

                        console.log('%cPage Layout Handles', headerStyle);
                        handles.forEach(function(handle) {
                            console.log('%c  - ' + handle, handleStyle);
                        });
                    }
                })();
               JS;
    }
}
