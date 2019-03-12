<?php
/**
 * GiaPhuGroup Co., Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GiaPhuGroup.com license that is
 * available through the world-wide-web at this URL:
 * https://www.giaphugroup.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    PHPCuong
 * @package     PHPCuong_ProductOnSale
 * @copyright   Copyright (c) 2019-2020 GiaPhuGroup Co., Ltd. All rights reserved. (http://www.giaphugroup.com/)
 * @license     https://www.giaphugroup.com/LICENSE.txt
 */

namespace PHPCuong\ProductOnSale\Observer;

class CatalogProductSaveAfterObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \PHPCuong\ProductOnSale\Model\OnSale
     */
    protected $productOnSale;

    /**
     * @param \PHPCuong\ProductOnSale\Model\OnSale $productOnSale
     */
    public function __construct(
        \PHPCuong\ProductOnSale\Model\OnSale $productOnSale
    ) {
        $this->productOnSale = $productOnSale;
    }

    /**
     * Set the value to the product attribute named on_sale
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        $this->productOnSale->setParentProductsToOnSale($product->getId());
    }
}
