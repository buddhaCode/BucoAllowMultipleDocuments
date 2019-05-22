<?php

namespace BucoAllowMultipleDocuments\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\CachedConfigReader;
use Shopware\Models\Shop\Shop;

class Document implements SubscriberInterface
{
    /**
     * @var CachedConfigReader
     */
    private $configReader;

    /**
     * @var string
     */
    private $pluginName;

    /**
     * @var ModelManager
     */
    private $em;

    /**
     * @var \Shopware\Models\Shop\Repository
     */
    private $shopRepo;

    public function __construct(CachedConfigReader $configReader, ModelManager $em, string $pluginName)
    {
        $this->configReader = $configReader;
        $this->pluginName = $pluginName;
        $this->em = $em;

        $this->shopRepo = $em->getRepository(Shop::class);
    }

    public static function getSubscribedEvents() : array
    {
        return [
            'Shopware_Components_Document::render::before' => 'injectAllowMultipleConfig',
        ];
    }

    public function injectAllowMultipleConfig(\Enlight_Hook_HookArgs $args)
    {
        /** @var \Shopware_Components_Document $subject */
        $subject = $args->getSubject();

        try {
            // respect previously set option
            if(empty($subject->_config['_allowMultipleDocuments'])) {
                $shop = $this->shopRepo->getById($subject->_subshop['id']);
                $config = $this->configReader->getByPluginName($this->pluginName, $shop);

                // Be minimally invasive. Just change what we need. Hello other sneaky devs ;)
                if(in_array($subject->_typID, $config['docTypeIds'])) {
                    $subject->_allowMultipleDocuments = true;
                }
            }
        }
        catch (\Exception $e) {}
    }
}