<?php

namespace dmstr\oauth\traits;

trait UserIdAttributeTrait
{
    /**
     * Identifier attribute of the user model
     *
     * @var string
     */
    protected string $_userIdAttribute;

    /**
     * @return string
     */
    public function getUserIdAttribute(): string
    {
        return $this->_userIdAttribute;
    }

    /**
     * @param string $userIdAttribute
     */
    public function setUserIdAttribute(string $userIdAttribute): void
    {
        $this->_userIdAttribute = $userIdAttribute;
    }
}
