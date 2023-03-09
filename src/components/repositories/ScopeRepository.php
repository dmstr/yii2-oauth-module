<?php

namespace dmstr\oauth\components\repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use dmstr\oauth\components\entities\ScopeEntity;
use yii\base\BaseObject;

class ScopeRepository extends BaseObject implements ScopeRepositoryInterface
{

    /**
     * @var array|array[]
     */
    protected array $_scopes = [
        'default' => [
            'description' => 'Default scope'
        ]
    ];

    /**
     * @inheritdoc
    */
    public function getScopeEntityByIdentifier($identifier)
    {
        if (!array_key_exists($identifier, $this->_scopes)) {
            return null;
        }
        $scope = new ScopeEntity();
        $scope->setIdentifier($identifier);
        return $scope;
    }

    /**
     * @inheritdoc
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        return [
            $this->getScopeEntityByIdentifier('default')
        ];
    }
}
