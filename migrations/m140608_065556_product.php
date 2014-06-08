<?php

use yii\db\Schema;

/**
 * Products table.
 */

class m140608_065556_product extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('product', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
        ]);

        // Creating 25 000 products.
        $thousands = 1;
        while ($thousands <= 25) {
            $rows = array();
            $koef = 1000 * ($thousands-1);
            for ($i = $koef; $i < $koef + 1000; $i++) { 
                $rows[] = [sprintf('%d product', $i + 1)];
            }

            // Insert each 1000 of products.
            $this->batchInsert('product', ['name'], $rows);

            ++$thousands;
        }
    }

    public function down()
    {
        $this->dropTable('product');
    }
}
