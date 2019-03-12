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

namespace PHPCuong\ProductOnSale\Model;

class OnSale
{
    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $catalogProductTypeConfigurable;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Timezone
     */
    protected $dateTimeFormater;

    /**
     * @param \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Stdlib\DateTime\Timezone $dateTimeFormater
     */
    public function __construct(
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Stdlib\DateTime\Timezone $dateTimeFormater
    ) {
        $this->catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
        $this->productFactory = $productFactory;
        $this->dateTimeFormater = $dateTimeFormater;
    }

    /**
     * Set the value to the product attribute named on_sale
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return int $isOnSale
     */
    public function getProductOnSale($product)
    {
        $now = $this->dateTimeFormater->date();
        $isOnSale = 1;

        $productType = $product->getTypeId();
        if ($productType == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $simpleProducts = $product->getTypeInstance()->getSalableUsedProducts($product);
            foreach ($simpleProducts as $simpleProduct) {
                $specialPrice = $simpleProduct->getSpecialPrice();
                $specialPriceFromDate = $simpleProduct->getSpecialFromDate();
                $specialPriceToDate = $simpleProduct->getSpecialToDate();
                if ($simpleProduct->getIsSalable() && $specialPrice && ($specialPrice < $simpleProduct->getPrice()) && (($specialPriceFromDate <= $now && $specialPriceToDate >= $now) || (($specialPriceFromDate <= $now && $specialPriceFromDate != NULL) && $specialPriceToDate  == ''))) {
                    $isOnSale = 2;
                    break;
                }
            }
        } else {
            $specialPrice = $product->getSpecialPrice();
            $specialPriceFromDate = $product->getSpecialFromDate();
            $specialPriceToDate = $product->getSpecialToDate();
            if ($product->getIsSalable() && $specialPrice && ($specialPrice < $product->getPrice()) && (($specialPriceFromDate <= $now && $specialPriceToDate >= $now) || (($specialPriceFromDate <= $now && $specialPriceFromDate != NULL) && $specialPriceToDate  == ''))) {
                $isOnSale = 2;
            }
        }

        return $isOnSale;
    }

    /**
     * Set the Parent Products to On Sale
     *
     * @param int $productId
     * @return void
     */
    public function setParentProductsToOnSale($productId)
    {
        $parentProductIds = $this->catalogProductTypeConfigurable->getParentIdsByChild($productId);
        foreach ($parentProductIds as $productId) {
            $product = $this->productFactory->create()->load($productId);
            if ($product->getId()) {
                try {
                    $product->save();
                } catch (\Exception $e) {}
            }
        }
    }
}
