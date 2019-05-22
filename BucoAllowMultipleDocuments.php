<?php

namespace BucoAllowMultipleDocuments;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\UninstallContext;

class BucoAllowMultipleDocuments extends Plugin
{
    const CACHE_LIST = [
        InstallContext::CACHE_TAG_PROXY,
        InstallContext::CACHE_TAG_CONFIG,
        InstallContext::CACHE_TAG_TEMPLATE,
    ];

    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(self::CACHE_LIST);
    }

    public function deactivate(DeactivateContext $context)
    {
        $context->scheduleClearCache(self::CACHE_LIST);
    }

    public function uninstall(UninstallContext $context)
    {
        $context->scheduleClearCache(self::CACHE_LIST);
    }
}