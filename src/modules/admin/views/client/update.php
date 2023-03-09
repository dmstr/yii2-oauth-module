<?php
/**
 * /app/runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\oauth\modules\admin\models\Client $model
 */
$this->title = Yii::t('oauth', 'Client');
$this->params['breadcrumbs'][] = ['label' => Yii::t('oauth', 'Client'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('oauth', 'Edit');
?>
<div class="giiant-crud client-update">

    <h1>
                <?php echo Html::encode($model->name) ?>

        <small>
            <?php echo Yii::t('oauth', 'Client') ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('oauth', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
