<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '5G');
error_reporting(E_ALL);

use Magento\Framework\App\Bootstrap;
require '../app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();

$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

?>

<?php
$_helper = $objectManager->create('\Magento\Catalog\Helper\Data');
$collection = $objectManager->create('\Magento\Catalog\Model\Product')->getCollection()->getData();
$csvHeader = "SKU,ProductUrl,ImageUrl\n";
$csvdata = "";
foreach ($collection as $key => $value) {
	// echo $value['entity_id'];
	$product = $objectManager->create('\Magento\Catalog\Model\Product')->load($value['entity_id']);
	$csvdata .= $product->getSku() . "," . $product->getProductUrl() . ",";
	$images = $product->getMediaGalleryImages();
	foreach ($images as $child) {
		$csvdata .= $child->getUrl() . ",";
	}
	$csvdata .= "\n";
}
?>
<?php
$csvDataCollection = $csvHeader . $csvdata;
$fileFactory = $objectManager->create('\Magento\Framework\App\Response\Http\FileFactory');
$fileFactory->create('ProductData.csv', $csvDataCollection, \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
exit();
?>
