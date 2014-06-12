<?php

/**
 * Products table.
 */

use yii\db\Schema;
use app\models\Product;

class m140608_065556_product extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('product', [
            'id'   => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
        ]);

        // Creating products.
        $rows = [];
        
        for ($i = 1; $i <= Product::PRODUCTS_COUNT; ++$i) {
            $rows[] = [sprintf('Product #%d', $i)];
            
            // Insert them into the table.
            if ($i % 5000 == 0 || ($i+1) > Product::PRODUCTS_COUNT) {
                $this->batchInsert('product', ['name'], $rows);
                $rows = [];
            }
        }

    }

    public function down()
    {
        $this->dropTable('product');
    }
}
