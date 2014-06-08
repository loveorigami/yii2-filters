<?php

use yii\db\Schema;

/**
 * Filters table.
 */

class m140608_072357_filter extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('filter', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
        ]);

        // Creating 200 of filters.
        $rows = [];
        for ($i = 1; $i <= 200; ++$i) {
            $rows[] = [sprintf('Filter #%d', $i)];
        }
        
        // Insert them into the table.
        $this->batchInsert('filter', ['name'], $rows);
    }

    public function down()
    {
        $this->dropTable('filter');
    }
}
