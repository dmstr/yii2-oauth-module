<?php

namespace dmstr\oauth\components\entities;

use DateTimeImmutable;
use Lcobucci\JWT\Token\Plain;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use dmstr\oauth\traits\IssuerTrait;
use dmstr\oauth\traits\UserIdAttributeTrait;
use yii\base\BaseObject;

/**
 * @property-write array $scopes
*/
class AccessTokenEntry extends BaseObject implements AccessTokenEntityInterface
{
    use AccessTokenTrait, TokenEntityTrait, EntityTrait, IssuerTrait, UserIdAttributeTrait;

    /**
     * @inheritdoc
     */
    public function getUserIdentifier()
    {
        // Get access token user's uuid if there is a user connected
        $user = $this->client->accessTokenUser();
        return $user->{$this->getUserIdAttribute()} ?? null;
    }

    /**
     * Overwrite from AccessTokenTrait::convertToJWT()
     * Generate a JWT from the access token
     *
     * @return Plain
     */
    private function convertToJWT()
    {
        // Overwrite to add sub claim via the relatedTo method. Everything else is copied over from trait
        // Expired set is set, when instantiating the authorisation server e.g. in the TokenController
        $this->initJwtConfiguration();
        $builder = $this->jwtConfiguration->builder()
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier())
            ->issuedAt(new DateTimeImmutable())
            ->canOnlyBeUsedAfter(new DateTimeImmutable())
            ->expiresAt($this->getExpiryDateTime())
            ->relatedTo((string)$this->getUserIdentifier())
            ->withClaim('scopes', $this->getScopes());

        // Check if the issuer is set before adding it to the token
        if (!empty($this->getIssuer())) {
            $builder->issuedBy($this->getIssuer());
        }

        return $builder->getToken($this->jwtConfiguration->signer(), $this->jwtConfiguration->signingKey());
    }

    /**
     * Accessed via magic setter in AccessTokenRepository
     *
     * @param array $scopes
     *
     * @return void
     */
    public function setScopes(array $scopes)
    {
        foreach ($scopes as $scope) {
            $this->addScope($scope);
        }
    }
}
