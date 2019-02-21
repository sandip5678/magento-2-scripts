<?php

namespace CategoryFix\CategoryAttribute\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class ImageUseDefaultFix implements ObserverInterface {

    protected $_request;
    protected $scopeConfig;
    protected $_productFactory;
    protected $stockRegistry;
    protected $_directoryList;
    protected $file;
    protected $datetime;
    protected $_responseFactory;
    protected $_url;
    protected $_indexerFactory;
	protected $_indexerCollectionFactory;

    public function __construct(
		\Magento\Catalog\Block\Product\Context $context,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Catalog\Model\ProductFactory $productFactory, \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry, \Magento\Framework\App\Filesystem\DirectoryList $directoryList, \Magento\Framework\Filesystem\Io\File $file,
		\Magento\Framework\Stdlib\DateTime\DateTime $datetime,
		\Magento\Framework\App\ResponseFactory $responseFactory,
		\Magento\Framework\UrlInterface $url,
		\Magento\Indexer\Model\IndexerFactory $indexerFactory,
		\Magento\Indexer\Model\Indexer\CollectionFactory $indexerCollectionFactory
    ) {
        $this->_request = $context->getRequest();
        $this->scopeConfig = $scopeConfig;
        $this->_productFactory = $productFactory;
        $this->stockRegistry = $stockRegistry;
        $this->_directoryList = $directoryList;
        $this->file = $file;
        $this->datetime = $datetime;
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_indexerFactory = $indexerFactory;
		$this->_indexerCollectionFactory = $indexerCollectionFactory;
    }

     
	 

    public function execute(EventObserver $observer) {

	
		//https://github.com/magento/magento2/issues/12159
		$obWriter = new \Zend\Log\Writer\Stream(BP . '/var/log/ImageUseDefaultFix.log');
		$obLogger = new \Zend\Log\Logger();
		$obLogger->addWriter( $obWriter );
		
		$obLogger->log(1, "--- ImageUseDefaultFix call - start" );
		
		/** @var Category $category */
		$category = $observer->getEvent()->getCategory();
		$categoryPostData = $observer->getEvent()->getRequest()->getPostValue();

		if(empty($categoryPostData)){
			return;
		}

		/**
		 * Check "Use Default Value" checkboxes values
		 */
		if (isset($categoryPostData['use_default']) && !empty($categoryPostData['use_default'])) {
        foreach ($categoryPostData['use_default'] as $attributeCode => $attributeValue) {
			$obLogger->log(1, "--- ImageUseDefaultFix call -loop" );
            if (empty($attributeValue)) {
                continue;
            }
            /** @var Attribute $attribute */
            $attribute = $category->getAttributes()[$attributeCode];
            if($attribute->isStatic()){
                continue;
            }
            if(!in_array($attribute->getBackendType(), ['varchar', 'text', 'datetime'])){
                continue;
            }
            $attribute->setIsRequired(false);
            $category->setData($attributeCode, false);
            $category->lockAttribute($attributeCode);
			}
		}		
		$obLogger->log(1, "--- ImageUseDefaultFix call--end" );		 
    }

}
