<?php
error_reporting(0);
use Magento\Framework\App\Bootstrap;

require __DIR__ . '/../app/bootstrap.php';
 
$params = $_SERVER;
 
$bootstrap = Bootstrap::create(BP, $params);
 
$obj = $bootstrap->getObjectManager();
 
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('adminhtml');


$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
$registry = $objectManager->create('\Magento\Framework\Registry');
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);



for( $cnt=1; $cnt<2000; $cnt++) {
	try{
		$_product = $objectManager->create('Magento\Catalog\Model\Product');
$_product->setName('Product Name' . $cnt);
$_product->setTypeId('simple');
$_product->setAttributeSetId(4);
$_product->setSku('M225-SKU-'.$cnt);
$_product->setWebsiteIds(array(1));
$_product->setVisibility(4);
$_product->setWeight(4);
$_product->setTaxClassId(0);
$_product->setPrice(10.00);
$_product->setStockData(array(
	'use_config_manage_stock' => 0,
    'manage_stock' => 1,
    'min_sale_qty' => 1,
    'max_sale_qty' => 2,
    'is_in_stock' => 1,
    'qty' => 10000
));

	$_product->save();			
	echo 'Product Saved'.$_product->getId().PHP_EOL;	
	} catch(Exception $e) {
		echo 'Exception thrown'.$e;
	}

}			


