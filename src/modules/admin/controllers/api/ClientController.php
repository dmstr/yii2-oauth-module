<?php

namespace dmstr\oauth\modules\admin\controllers\api;

use dmstr\oauth\modules\admin\models\Client;

/**
* This is the class for REST controller "ClientController".
*/

class ClientController extends \yii\rest\ActiveController
{
    public $modelClass = Client::class;
}
