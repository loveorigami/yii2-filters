<?php

use yii\db\Schema;

/**
 * Product-filter pivot table.
 */

class m140608_072835_product_filter_link extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('product_filter_link', [
            'id' => Schema::TYPE_PK,
            'productId' => Schema::TYPE_INTEGER . '(10) NULL',
            'filterId' => Schema::TYPE_INTEGER . '(10) NULL',
            'status' => Schema::TYPE_SMALLINT . ' DEFAULT "0"',
            'KEY `productId` (`productId`)',
            'KEY `filterId` (`filterId`)',
            'KEY `full` (`productId`, `filterId`)',
        ]);

        // $this->addForeignKey('fk_product_filter_link_product', 'product_filter_link', 'productId', 'product', 'id');
        // $this->addForeignKey('fk_product_filter_link_filter', 'product_filter_link', 'filterId', 'filter', 'id');

        // We got 25k of products and 200 filters.
        // We should relate them.
        // So we checking 25- of filters for each product.
        $rows = array();
        for ($i = 1; $i <= 25000; ++$i) {
            $busy = [];
            for ( $k = 1; $k <= 25; ++$k ) {
                $filterId = rand(1, 200);
                if (!in_array($filterId, $busy)) {
                    $rows[] = [$i, $filterId];
                    $busy[] = $filterId;
                }
            }
            if ($i % 1000 == 0) {
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
