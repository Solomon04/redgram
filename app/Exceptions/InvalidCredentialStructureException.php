<?php


namespace App\Exceptions\Filesystem;

use \Exception;

class InvalidCredentialStructureException extends Exception
{
    protected $message = 'Invalid credential structure';
}
