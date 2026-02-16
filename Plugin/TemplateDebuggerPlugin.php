<?php

namespace ZegacLabs\DevAccelerator\Plugin;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\State;
use Magento\Framework\View\Element\Template;
use ZegacLabs\DevAccelerator\Helper\Config;

class TemplateDebuggerPlugin
{
    private string $rootPath;
    private ?bool $isEnabledCache = null;

    public function __construct(
        DirectoryList $directoryList,
        private readonly State $appState,
        private readonly Config $configHelper
    ) {
        $this->rootPath = $directoryList->getRoot() . '/';
    }

    /**
     * This plugin intercepts the rendering of template blocks and adds HTML
     * comments that display the template file path.
     *
     * @param Template $subject
     * @param mixed $result
     * @return mixed
     */
    public function afterToHtml(Template $subject, mixed $result): mixed
    {
        if (!$this->isEnabled() || !is_string($result)) {
            return $result;
        }

        $template = $subject->getTemplate();
        if (!$template) {
            return $result;
        }

        $templateFile = $subject->getTemplateFile();
        if (!$templateFile || !is_file($templateFile)) {
            return $result;
        }

        $relativePath = str_replace($this->rootPath, '', $templateFile);

        $safePath = htmlspecialchars($relativePath, ENT_QUOTES, 'UTF-8');
        $debugOutput = "<!-- TEMPLATE START: {$safePath} -->\n";
        $debugOutput .= $result;
        $debugOutput .= "\n<!-- TEMPLATE END: {$safePath} -->\n";

        return $debugOutput;
    }

    /**
     * Check if template debugger feature is enabled with result caching
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

        $this->isEnabledCache = $this->configHelper->isTemplateDebuggerEnabled();

        return $this->isEnabledCache;
    }
}
