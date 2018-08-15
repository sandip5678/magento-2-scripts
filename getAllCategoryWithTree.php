<?php

use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);

$obj = $bootstrap->getObjectManager();

$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$quote = $obj->get('Magento\Checkout\Model\Session')->getQuote()->load(1);

$categoryCollection = $obj->create('Magento\Catalog\Model\Category');
$category           = $categoryCollection->load(1);
$subcats            = $category->getChildrenCategories();
?>
<ul>
<?php foreach($subcats as $subcat) { ?>
<li>Name:
    <?php echo $subcat->getName(); ?> Id:
    <?php echo $subcat->getId(); ?>
    <?php if ($subcat->getChildrenCategories()):?>
    <ul>
        <?php foreach($subcat->getChildrenCategories() as $subcat2): ?>
        <li> Name:
            <?php echo $subcat2->getName(); ?> Id:
            <?php echo $subcat2->getId(); ?>
        </li>
        <?php if ($subcat2->getChildrenCategories()): ?>
        <ul>
            <?php foreach($subcat2->getChildrenCategories() as $subcat3): ?>
            <li> Name:
                <?php echo $subcat3->getName(); ?> Id:
                <?php echo $subcat3->getId(); ?>
                <?php if ($subcat3->getChildrenCategories()): ?>
                <ul>
                    <?php foreach($subcat3->getChildrenCategories() as $subcat4): ?>
                    <li> Name:
                        <?php echo $subcat4->getName(); ?> Id:
                        <?php echo $subcat4->getId(); ?>
                        <?php if ($subcat4->getChildrenCategories()): ?>
                        <ul>
                            <?php foreach($subcat4->getChildrenCategories() as $subcat5): ?>
                            <li> Name:
                                <?php echo $subcat5->getName(); ?> Id:
                                <?php echo $subcat5->getId(); ?>
                                <?php if ($subcat5->getChildrenCategories()): ?>
                                <ul>
                                    <?php foreach($subcat5->getChildrenCategories() as $subcat6): ?>
                                    <li> Name:
                                        <?php echo $subcat6->getName(); ?> Id:
                                        <?php echo $subcat6->getId(); ?> </li>

                            </li>


                    </li>

            </li>
            <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <?php endforeach; ?>

            </ul>

            <?php endif; ?>
            <?php endforeach; ?>

        </ul>

        <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?> </li>
<?php } ?>
</ul>
