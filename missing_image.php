<?php

            ini_set('memory_limit', '-1');

            define('DS', DIRECTORY_SEPARATOR);

            use \Magento\Framework\App\Bootstrap;

include('../app/bootstrap.php');
            $bootstrap = Bootstrap::create(BP, $_SERVER);
            $objectManager = $bootstrap->getObjectManager();
            $app_state = $objectManager->get('\Magento\Framework\App\State');
            $app_state->setAreaCode('frontend');

            $pc = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
            $pc->addAttributeToSelect('*');


            foreach ($pc as $p) {

             if ($p->getImage() == '' || $p->getImage() == 'no_selection') {
              echo $p->getSku() . PHP_EOL;
              // die;
             }
            }
 
