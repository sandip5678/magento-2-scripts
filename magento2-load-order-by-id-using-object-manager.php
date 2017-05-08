$orderID = 111;
$_objManager = \Magento\Framework\App\ObjectManager::getInstance();
$order = $this->_objManager->create('Magento\Sales\Model\Order');
$order ->load($orderID);
