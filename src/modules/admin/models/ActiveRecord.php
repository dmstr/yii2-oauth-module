<?php

namespace dmstr\oauth\modules\admin\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

abstract class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
    */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if ($this->hasAttribute('created_by') && $this->hasAttribute('updated_by')) {
            $behaviors['blamable'] = [
                'class' => BlameableBehavior::class
            ];
        }
        if ($this->hasAttribute('created_at') && $this->hasAttribute('updated_at')) {
            $behaviors['timestamp'] = [
                'class' => TimestampBehavior::class,
                'value' => date('Y-m-d H:i:s')
            ];
        }

        return $behaviors;
    }
}
