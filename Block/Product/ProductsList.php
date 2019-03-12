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

namespace PHPCuong\ProductOnSale\Block\Product;

class ProductsList extends \Magento\CatalogWidget\Block\Product\ProductsList
    implements \Magento\Widget\Block\BlockInterface
{
    /**
     * Prepare and return product collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function createCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*')->addAttributeToFilter(
            'status', ['in' => [\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED]]
        )->setVisibility(
            $this->catalogProductVisibility->getVisibleInCatalogIds()
        )->addAttributeToFilter(
            'on_sale', '2'
        )->setPageSize($this->getProductsCount())->setCurPage(1);

        return $collection;
    }

    /**
     * Retrieve how many products should be displayed
     *
     * @return int
     */
    public function getProductsCount()
    {
        $limit = (int)$this->getData('products_count');
        if ($limit <= 0) {
            return 10;
        }
        return $limit;
    }

    /**
     * Render pagination HTML
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return '';
    }
}
