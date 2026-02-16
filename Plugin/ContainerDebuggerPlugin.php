<?php

namespace ZegacLabs\DevAccelerator\Plugin;

use Magento\Framework\App\State;
use Magento\Framework\View\Layout;
use ZegacLabs\DevAccelerator\Helper\Config;

class ContainerDebuggerPlugin
{
    private ?bool $isEnabledCache = null;

    public function __construct(
        private readonly State  $appState,
        private readonly Config $configHelper
    ) {
    }

    /**
     * This plugin intercepts the rendering of all elements and checks if they
     * are containers. For containers, it adds HTML comments that display the
     * container name.
     *
     * @param Layout $subject
     * @param mixed $result
     * @param string $name
     * @return mixed
     */
    public function afterRenderElement(
        Layout $subject,
        mixed $result,
        string $name
    ): mixed {
        if (!$this->isEnabled() || !is_string($result)) {
            return $result;
        }

        if (empty($name)) {
            return $result;
        }

        if (empty($result)) {
            return $result;
        }

        if (!$subject->isContainer($name)) {
            return $result;
        }

        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $debugOutput = "<!-- CONTAINER START: {$safeName} -->\n";
        $debugOutput .= $result;
        $debugOutput .= "\n<!-- CONTAINER END: {$safeName} -->\n";

        return $debugOutput;
    }

    /**
     * Check if container debugger feature is enabled with result caching
     * @return bool
     */
    private function isEnabled(): bool
    {
        if ($this->isEnabledCache !== null) {
            return $this->isEnabledCache;
        }

        if ($this->appState->getMode() !== State::MODE_DEVELOPER) {
            return $this->isEnabledCache = false;
        }

        $this->isEnabledCache = $this->configHelper->isContainerDebuggerEnabled();

        return $this->isEnabledCache;
    }
}
