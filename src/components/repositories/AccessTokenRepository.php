<?php

namespace dmstr\oauth\components\repositories;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use dmstr\oauth\components\entities\AccessTokenEntry;
use dmstr\oauth\traits\IssuerTrait;
use dmstr\oauth\traits\UserIdAttributeTrait;
use yii\base\BaseObject;

/**
 * @property string $issuer
 * @property string $userIdAttribute
*/
class AccessTokenRepository extends BaseObject implements AccessTokenRepositoryInterface
{
    use IssuerTrait, UserIdAttributeTrait;

    /**
     * @inheritdoc
    */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        return new AccessTokenEntry([
            'issuer' => !empty($this->getIssuer()) ? $this->getIssuer() : null, // Check if the issuer is set before adding it to the token
            'userIdAttribute' => $this->getUserIdAttribute(),
            'client' => $clientEntity,
            'identifier' => uniqid('jwt', true),
            'scopes' => $scopes,
            'userIdentifier' => $userIdentifier
        ]);
    }

    /**
     * @inheritdoc
    */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        return $accessTokenEntity;
    }

    /**
     * @inheritdoc
     */
    public function revokeAccessToken($tokenId)
    {
        // Revoking ist not implemented
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isAccessTokenRevoked($tokenId)
    {
        return false;
    }
}
