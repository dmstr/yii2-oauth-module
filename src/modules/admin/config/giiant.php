<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2023 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\oauth\modules\admin;

use schmunk42\giiant\generators\crud\providers\core\RelationProvider;
use schmunk42\giiant\commands\BatchController;
use schmunk42\giiant\generators\crud\callbacks\base\Callback;
use schmunk42\giiant\generators\crud\providers\core\CallbackProvider;
use schmunk42\giiant\generators\crud\providers\core\OptsProvider;

// levels of directory nesting may vary
$config = require dirname(__DIR__, 7) . '/config/main.php';

\Yii::$container->set(
    CallbackProvider::class,
    [
        // form
        'activeFields' => [
            'created_at|updated_at|created_by|updated_by' => Callback::false(),
            'plainSecret' => function ($attribute) {
                return <<<PHP
\$form->field(\$model, '$attribute')->passwordInput();
PHP;
            },
            'access_token_user_id' => function ($attribute) {
                return <<<PHP
\$form->field(\$model, '$attribute')->widget(kartik\select2\Select2::class, [
                'data' => \$model->accessTokenUser ? [\$model->$attribute => \$model->accessTokenUser->username ?? '?'] : [],
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
            ]);
PHP;
            },
        ],
        // index
        'columnFormats' => [
            'created_at|updated_at|created_by|updated_by' => Callback::false(),
        ],
        // view
        'attributeFormats' => [
            'created_at|updated_at' => function ($attribute) {
                return "'$attribute:datetime'";
            },
            'created_by|updated_by' => function ($attribute) {
                return <<<PHP
[
    'attribute' => '$attribute',
    'value' => function (\$model) {
        \$user = dmstr\oauth\modules\admin\models\User::findOne(\$model->$attribute);
        if (\$user) {
            return yii\helpers\Html::a(\$user->label, ['/oauth/admin/user/view', 'id' => \$model->$attribute]);
        }
        return null;
    },
    'format' => 'raw'
]
PHP;
            }
        ]
    ]
);

\Yii::$container->set(RelationProvider::class, [
    'inputWidget' => 'select2'
]);

$config['controllerMap']['oauth-batch'] = [
    'class' => BatchController::class,
    'overwrite' => true,
    'interactive' => false,
    'modelNamespace' => __NAMESPACE__ . '\\models',
    'modelBaseClass' => __NAMESPACE__ . '\\models\\ActiveRecord',
    'modelQueryNamespace' => __NAMESPACE__ . '\\models\\query',
    'crudControllerNamespace' => __NAMESPACE__ . '\\controllers',
    'crudSearchModelNamespace' => __NAMESPACE__ . '\\models\\search',
    'crudViewPath' => '@' . str_replace('\\', '/', __NAMESPACE__) . '/views',
    'crudPathPrefix' => '/oauth/admin/',
    'crudTidyOutput' => true,
    'crudFixOutput' => true,
    'crudAccessFilter' => false,
    'useTimestampBehavior' => false,
    'useBlameableBehavior' => false,
    'singularEntities' => false,
    'tablePrefix' => 'app_oauth_', // You may need to change app_ to your prefix
    'crudMessageCategory' => 'oauth',
    'modelMessageCategory' => 'oauth',
    'tables' => [
        'app_oauth_client'
    ],
    'crudProviders' => [
        CallbackProvider::class,
        OptsProvider::class,
        RelationProvider::class,
    ]
];

return $config;
