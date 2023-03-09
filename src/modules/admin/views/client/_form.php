<?php
/**
 * /app/runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;

/**
 *
 * @var yii\web\View $this
 * @var dmstr\oauth\modules\admin\models\Client $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="client-form">

    <?php $form = ActiveForm::begin(
    [
        'id' => 'Client',
        'layout' => 'horizontal',
        'enableClientValidation' => true,
        'errorSummaryCssClass' => 'error-summary alert alert-danger',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                //'offset' => 'col-sm-offset-4',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]
);
?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>


<!-- attribute id -->
			<?php echo $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

<!-- attribute name -->
			<?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<!-- attribute plainSecret -->
			<?php echo $form->field($model, 'plainSecret')->passwordInput(); ?>

<!-- attribute plainSecretRepeat -->
			<?php echo $form->field($model, 'plainSecretRepeat')->passwordInput(); ?>

<!-- attribute access_token_user_id -->
			<?php echo $form->field($model, 'access_token_user_id')->widget(kartik\select2\Select2::class, [
        'data' => $model->accessTokenUser ? [$model->access_token_user_id => $model->accessTokenUser->username ?? '?'] : [],
        'theme' => kartik\select2\Select2::THEME_BOOTSTRAP,
        'options' => [
            'placeholder' => \Yii::t('oauth', 'Please select a user')
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new yii\web\JsExpression("function () { return '" . \Yii::t('oauth', 'Waiting for results...') . "'; }"),
            ],
            'ajax' => [
                'url' => yii\helpers\Url::to(['user-list']),
                'dataType' => 'json',
                'data' => new yii\web\JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new yii\web\JsExpression('function (markup) { return markup; }'),
            'templateResult' => new yii\web\JsExpression('function(user) { return user.text; }'),
            'templateSelection' => new yii\web\JsExpression('function (user) { return user.text; }'),
        ],
    ]); ?>

<!-- attribute created_at -->

<!-- attribute updated_at -->

<!-- attribute created_by -->

<!-- attribute updated_by -->
        </p>
        <?php $this->endBlock(); ?>

        <?php echo
Tabs::widget(
    [
        'encodeLabels' => false,
        'items' => [
            [
                'label'   => Yii::t('oauth', 'Client'),
                'content' => $this->blocks['main'],
                'active'  => true,
            ],
        ]
    ]
);
?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?php echo Html::submitButton(
    '<span class="glyphicon glyphicon-check"></span> ' .
    ($model->isNewRecord ? Yii::t('oauth', 'Create') : Yii::t('oauth', 'Save')),
    [
        'id' => 'save-' . $model->formName(),
        'class' => 'btn btn-success'
    ]
);
?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
