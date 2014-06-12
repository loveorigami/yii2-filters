<?php

/**
 * Product-filter pivot table.
 */

use yii\db\Schema;
use app\models\Product;
use app\models\Filter;

class m140608_072835_product_filter_link extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('product_filter_link', [
            'id'        => Schema::TYPE_PK,
            'productId' => Schema::TYPE_INTEGER . '(10) NULL',
            'filterId'  => Schema::TYPE_INTEGER . '(10) NULL',
            'status'    => Schema::TYPE_SMALLINT . ' DEFAULT "0"',
            'KEY `productId` (`productId`)',
            'KEY `filterId` (`filterId`)',
            'KEY `full` (`productId`, `filterId`)',
        ]);

        // $this->addForeignKey('fk_product_filter_link_product', 'product_filter_link', 'productId', 'product', 'id');
        // $this->addForeignKey('fk_product_filter_link_filter', 'product_filter_link', 'filterId', 'filter', 'id');

        // We got products and filters.
        // We should connect them.
        // So we checking filters for each product.

        $rows           = [];
        $added          = 0;
        $filtersToCheck = ceil(Filter::FILTERS_COUNT/8);

        for ($i = 1; $i <= Product::PRODUCTS_COUNT; ++$i) {

            $filtersChecked = [];

            for ( $k = 1; $k <= $filtersToCheck; ++$k ) {
                $filterId = rand(1, Filter::FILTERS_COUNT);
            
                if (!in_array($filterId, $filtersChecked)) {
                    $rows[]           = [$i, $filterId];
                    $filtersChecked[] = $filterId;
                    $added++;
                }
            }

            if ($i % 5000 == 0 || ($i+1) > Product::PRODUCTS_COUNT) {
                $this->batchInsert('product_filter_link', ['productId', 'filterId'], $rows);
                $rows = [];
            }
        }
    }

    public function down()
    {
        $this->dropTable('product_filter_link');
    }
}
