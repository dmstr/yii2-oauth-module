<?php
/**
 * /app/runtime/giiant/eeda5c365686c9888dbc13dbc58f89a1
 *
 * @package default
 */


use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\oauth\modules\admin\models\search\Client $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="client-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    		<?php echo $form->field($model, 'id') ?>

		<?php echo $form->field($model, 'name') ?>

		<?php echo $form->field($model, 'secret_hash') ?>

		<?php echo $form->field($model, 'access_token_user_id') ?>

		<?php echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at')?>

		<?php // echo $form->field($model, 'created_by')?>

		<?php // echo $form->field($model, 'updated_by')?>

    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('oauth', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton(Yii::t('oauth', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
