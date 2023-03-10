<?php

namespace dmstr\oauth\modules\admin\models;

/**
 * @inheritdoc
 *
 * @property-read string $label
 *
 * /!\ Just for giiant generation. Do not use /!\
*/
class User extends \Da\User\Model\User
{

    /**
     * Label to uniquely describe the user
     * @return string
     */
    public function getLabel()
    {
        return $this->username;
    }

}
