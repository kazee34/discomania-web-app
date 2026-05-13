<?php

namespace Src\shared\domain\exceptions;

class UserNotFoundException extends \DomainException
{
    public function __construct(int $id, string $type = 'User')
    {
        parent::__construct(sprintf('%s with id %d not found', $type, $id));
    }
}
