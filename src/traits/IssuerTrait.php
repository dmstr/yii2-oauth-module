<?php

namespace dmstr\oauth\traits;

trait IssuerTrait
{
    /**
     * Issuer of the token
     *
     * @var string|null
     */
    protected ?string $_issuer = null;

    /**
     * @return string|null
     */
    public function getIssuer(): ?string
    {
        return $this->_issuer;
    }

    /**
     * @param string|null $issuer
     */
    public function setIssuer(?string $issuer): void
    {
        $this->_issuer = $issuer;
    }
}
