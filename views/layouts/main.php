<?php
use app\assets\MinAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

MinAsset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->title; ?>/ basic filters</title>
    <?php $this->head(); ?>
</head>
<body>
    <?php $this->beginBody() ?>
    
    <div class="wrap">
        <div class="container">
            <?php echo $content ?>
        </div>
    </div>
    
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
