<?php

namespace app\models;

class Product extends \yii\db\ActiveRecord
{
    const PRODUCTS_COUNT = 50000;

    public static function tableName()
    {
        return 'product';
    }
}
