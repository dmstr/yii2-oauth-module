<?php

namespace dmstr\oauth\modules\admin\models;

use dmstr\oauth\modules\admin\models\base\Client as BaseClient;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "app_oauth_client".
 */
class Client extends BaseClient
{
    /**
     * @var string
     */
    public $plainSecret;

    /**
     * @var string
     */
    public $plainSecretRepeat;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['crud'] = [
            'id',
            'name',
            'plainSecret',
            'plainSecretRepeat',
            'access_token_user_id',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by'
        ];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            [
                'id',
                'name',
                'plainSecret',
                'plainSecretRepeat'
            ],
            'filter',
            'filter' => 'trim'
        ];
        $rules[] = [
            'id',
            'match',
            'pattern' => '/\s/',
            'not' => true,
            'message' => Yii::t('oauth', '{attribute} must not contain spaces')
        ];
        $rules[] = [
            'plainSecret',
            'string',
            'min' => 6
        ];
        $rules[] = [
            [
                'plainSecret',
                'plainSecretRepeat'
            ],
            'required'
        ];
        $rules[] = [
            'plainSecretRepeat',
            'compare',
            'compareAttribute' => 'plainSecret',
            'message' => Yii::t('oauth', 'Secrets must match')
        ];
        return $rules;
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function beforeValidate()
    {
        // Empty check not really needed now because secret is required but this may change in the future
        if (!empty($this->plainSecret)) {
            // Write secret hash by assigning it to hashed plain secret
            $this->setAttribute('secret_hash', Yii::$app->getSecurity()->generatePasswordHash($this->plainSecret, 10));
        }
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['plainSecret'] = Yii::t('oauth', 'Secret');
        $attributeLabels['plainSecretRepeat'] = Yii::t('oauth', 'Secret Repeat');
        return $attributeLabels;
    }

    public function attributeHints()
    {
        $attributeHints = parent::attributeHints();
        $attributeHints['access_token_user_id'] = Yii::t('oauth', 'Technical user whose ID is written to the access token in the sub claim. If no user is selected, the sub claim is omitted.');
        return $attributeHints;
    }

    /**
     * Do not implement this as a getter method because giiant will potentially generate this as a duplicated relation
     *
     * @return User|null
     */
    public function accessTokenUser(): ?User
    {
        return User::findOne($this->access_token_user_id);
    }
}
