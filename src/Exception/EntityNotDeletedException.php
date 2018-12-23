<?php

namespace App\Exception;

class EntityNotDeletedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("The entity could not be deleted.");
    }
}