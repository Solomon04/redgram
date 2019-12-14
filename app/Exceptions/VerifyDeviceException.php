<?php


namespace App\Exceptions\Filesystem;

use \Exception;

class VerifyDeviceException extends Exception
{
    protected $message = 'You must trust this device within the Instagram app.';
}
