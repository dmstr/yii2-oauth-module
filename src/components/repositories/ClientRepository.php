<?php

namespace dmstr\oauth\components\repositories;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use dmstr\oauth\components\entities\ClientEntity;
use Yii;
use yii\base\BaseObject;

class ClientRepository extends BaseObject implements ClientRepositoryInterface
{

    /**
     * @inheritdoc
     */
    public function getClientEntity($clientIdentifier)
    {
        return ClientEntity::findOne($clientIdentifier);
    }

    /**
     * @inheritdoc
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $client = $this->getClientEntity($clientIdentifier);
        return $client && Yii::$app->getSecurity()->validatePassword($clientSecret, $client->secret_hash);
    }
}
