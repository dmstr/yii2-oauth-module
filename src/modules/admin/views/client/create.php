<?php
/**
 * /app/runtime/giiant/fccccf4deb34aed738291a9c38e87215
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
$this->params['breadcrumbs'][] = ['label' => Yii::t('oauth', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud client-create">

    <h1>
                <?php echo Html::encode($model->name) ?>
        <small>
            <?php echo Yii::t('oauth', 'Client') ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo             Html::a(
    Yii::t('oauth', 'Cancel'),
    \yii\helpers\Url::previous(),
    ['class' => 'btn btn-default']
) ?>
        </div>
    </div>

    <hr />

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
