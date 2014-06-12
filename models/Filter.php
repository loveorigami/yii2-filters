<?php

namespace app\models;

class Filter extends \yii\db\ActiveRecord
{
    const FILTERS_COUNT = 200;
    
    public static function tableName()
    {
        return 'filter';
    }
}
