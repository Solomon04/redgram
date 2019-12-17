<?php


namespace App\Contracts\Instagram;


use App\Exceptions\Filesystem\CredentialsAreMissingException;
use App\Exceptions\Filesystem\VerifyDeviceException;
use App\Services\Filesystem\InstagramCredentialsManager;
use InstagramAPI\Instagram;

interface Authentication
{
    /**
     * Authentication constructor.
     * @param Instagram $instagram
     * @param InstagramCredentialsManager $manager
     */
    public function __construct(Instagram $instagram, InstagramCredentialsManager $manager);

    /**
     * Login to the user's Instagram account.
     *
     * @return \InstagramAPI\Response\LoginResponse|null
     * @throws CredentialsAreMissingException
     * @throws VerifyDeviceException
     * @throws \App\Exceptions\Filesystem\InvalidCredentialStructureException
     */
    public function login();
}
