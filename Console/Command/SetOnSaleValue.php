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

namespace PHPCuong\ProductOnSale\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetOnSaleValue extends Command
{
    /**
     * @var \Magento\Framework\App\State
     */
    protected $appState;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->appState = $appState;
        $this->productFactory = $productFactory;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        // Set the name and description for the new CLI command
        $this->setName('catalog:product:attribute:on_sale')
            ->setDescription('Set the value to the product attribute on_sale');
    }

    /**
     * Execute the codes to generate the product URLs
     *
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // We must set the area code
        $this->appState->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
        // Get all the stores view on your website
        $output->writeln('<info>Starting sets the value to the product attribute on_sale on each product</info>');
        // Get all the products
        $productCollection = $this->productFactory->create()->getCollection()->addAttributeToSelect(
            '*'
        );
        // Loop all products
        foreach ($productCollection as $product) {
            $output->writeln('<info>Start on the product named: '.$product->getName().'</info>');
            try {
                $product->save();
            } catch (\Exception $e) {}
        }
        $output->writeln('<info>Set the value to the product attribute on_sale on each product successful.</info>');
    }
}
