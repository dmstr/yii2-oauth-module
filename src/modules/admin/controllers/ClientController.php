<?php

namespace dmstr\oauth\modules\admin\controllers;

use dmstr\oauth\modules\admin\models\User;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * This is the class for controller "ClientController".
 */
class ClientController extends \dmstr\oauth\modules\admin\controllers\base\ClientController
{
    /**
     * Action to find and filter user via ajax. Used by crud when creating or updating a client
     *
     * @param $q
     * @param $id
     *
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     * @return Response
     */
    public function actionUserList($q = null, $id = null)
    {
        // Check if the current request comes in via ajax and throw an error if not
        if (!$this->request->getIsAjax()) {
            throw new ForbiddenHttpException('Do not access this page directly');
        }

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            // Find all users limited by a value so if there are to many there is no lag
            $query = new Query();
            $query->select(['id', 'text' => 'username'])
                ->from(User::tableName())
                ->where(['ILIKE', 'username', $q])
                ->limit(20);
            $data = $query->createCommand()->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            // On load find only the one given user.
            $user = User::findOne($id);
            $out['results'] = ['id' => $id, 'text' => $user->username ?? '?'];
        }

        return $this->asJson($out);
    }
}
