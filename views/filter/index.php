<?php

/**
 * @var \yii\web\View $this
 * @var app\models\Filter $filters 
 */

$this->title = 'Filtering products';
?> 

<?php if ($filters): ?>
    
    <?php foreach ($filters as $filter): ?> 
    <label class="checkbox">
        <input type="checkbox" class="j-filter" data-id="<?php echo $filter->id; ?>">
        <?php echo $filter->name; ?> 
    </label>
    <?php endforeach ?>
    
    <button class="btn btn-warning btn-fly hide" data-template="Found products: %d"></button>
<?php endif ?>
