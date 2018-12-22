<?php

namespace App\Exception;

use Doctrine\ORM\ORMException;

class EntityNotDeletedException extends ORMException
{
    public function __construct()
    {
        parent::__construct("The entity could not be deleted.");
    }
}