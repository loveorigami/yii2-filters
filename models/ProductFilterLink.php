<?php

namespace app\models;

class ProductFilterLink extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE  = 0;
    const STATUS_DELETED = 1;

    public static function tableName()
    {
        return 'product_filter_link';
    }
}
