<?php

/**
 * Filters table.
 */

use yii\db\Schema;
use app\models\Filter;

class m140608_072357_filter extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('filter', [
            'id'   => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
        ]);

        // Creating filters.
        $rows = [];

        for ($i = 1; $i <= Filter::FILTERS_COUNT; ++$i) {
            $rows[] = [sprintf('Filter #%d', $i)];
            
            // Insert them into the table.
            if ($i % 5000 == 0 || ($i+1) > Filter::FILTERS_COUNT) {
                $this->batchInsert('filter', ['name'], $rows);
                $rows = [];
            }
        }
    }

    public function down()
    {
        $this->dropTable('filter');
    }
}
