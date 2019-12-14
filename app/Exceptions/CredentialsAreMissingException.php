<?php


namespace App\Exceptions\Filesystem;

use \Exception;

class CredentialsAreMissingException extends Exception
{
    protected $message = 'User has no setup their credentials.';
}
