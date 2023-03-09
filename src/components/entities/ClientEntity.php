<?php

namespace dmstr\oauth\components\entities;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use dmstr\oauth\modules\admin\models\Client as ClientModel;

class ClientEntity extends ClientModel implements ClientEntityInterface
{

    /**
     * @inheritdoc
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getRedirectUri()
    {
        // Not implemented because it is not needed yet
        return '';
    }

    /**
     * @inheritdoc
     * @link https://www.oauth.com/oauth2-servers/definitions/#confidential-clients Definition of a confidential client
     */
    public function isConfidential()
    {
        return !empty($this->secret_hash);
    }
}
