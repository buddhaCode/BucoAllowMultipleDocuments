<?php

namespace BucoAllowMultipleDocuments\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\CachedConfigReader;
use Shopware\Models\Shop\Shop;

class Template implements SubscriberInterface
{
    /** @var string */
    private $pluginDir;

    /** @var ModelManager */
    private $em;

    /**@var CachedConfigReader */
    private $configReader;

    /** @var \Shopware\Models\Shop\Repository */
    private $shopRepo;

    /** @var string */
    private $pluginName;

    public function __construct(string $pluginDir, string $pluginName, ModelManager $em, CachedConfigReader $configReader)
    {
        $this->pluginDir = $pluginDir;
        $this->em = $em;
        $this->configReader = $configReader;

        $this->shopRepo = $em->getRepository(Shop::class);
        $this->pluginName = $pluginName;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Order' => 'extendExtJsOrder',
        ];
    }

    public function extendExtJsOrder(\Enlight_Event_EventArgs $args)
    {
        $view = $args->getSubject()->View();

        if ($args->getRequest()->getActionName() === 'load') {
            $view->addTemplateDir($this->pluginDir . '/Resources/views/');
            $view->extendsTemplate('backend/order/controller/detail-BucoAllowMultipleDocuments.js');

            /** @var Shop[] $shops */
            $shops = $this->shopRepo->findAll();
            $multipleAllowedDocIdsByShopId = [];
            foreach ($shops as $shop) {
                $multipleAllowedDocIdsByShopId[$shop->getId()] = $this->configReader->getByPluginName($this->pluginName, $shop)['docTypeIds'];
            }

            $view->assign('bucoMultipleAllowedDocIdsByShopId', $multipleAllowedDocIdsByShopId);
        }
    }
}
