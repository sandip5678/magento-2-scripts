<?php
define('DS', DIRECTORY_SEPARATOR); 
use \Magento\Framework\App\Bootstrap;
 
include('../app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$app_state = $objectManager->get('\Magento\Framework\App\State');
$app_state->setAreaCode('frontend');

function getProductswithImages()
{
    $file = 'product.csv';
    $arrResult = array();
    $headers = false;
    $handle = fopen($file, "r");
    if (empty($handle) === false) {
        while (($data = fgetcsv($handle, 1200, ",")) !== FALSE) {
            if (!$headers) {
                $headers[] = $data;
            } else {
                $arrResult[] = $data;
            }
        }
        fclose($handle);
    }
    return $arrResult;
}

$products_data = getProductswithImages();

$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/images.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter( $writer );

//$pc = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
//$products_data = $pc->addAttributeToSelect('sku','entity_id')->load();
			
foreach ( $products_data as $productsinfo ) {
	
	$sku = $productsinfo[0];	
	$base_image = $productsinfo[1];
	$small_image = $productsinfo[2];
	$thumbnail_image = $productsinfo[3];
	
	$loadproductRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
	$product = $loadproductRepository->get( $sku );

	$productRepository = $objectManager->create('Magento\Catalog\Api\ProductRepositoryInterface');
	$existingMediaGalleryEntries = $product->getMediaGalleryEntries();
	foreach ( $existingMediaGalleryEntries as $key => $entry ) {
    	unset( $existingMediaGalleryEntries[$key] );
	}

	$filesystem = $objectManager->create('Magento\Framework\Filesystem');
    $mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    $mediaPath = $mediaDirectory->getAbsolutePath();

    
	if ( $base_image != '' ) {
        $image_directory = $mediaPath.'import/'.$base_image;        
        if ( file_exists( $image_directory ) ) {
            $product->setMediaGallery( array('images' => array(), 'values' => array()))
                ->addImageToMediaGallery($image_directory, array('image', 'thumbnail', 'small_image'), false, false);
				
           	$product->save();			
			echo $product->getSku(). PHP_EOL;
			
        } else {
			
			$logger->info( $product->getSku() );
        }
		
    }
}
