<?php


namespace App\Services\Instagram;


use App\Contracts\Instagram\Authentication;
use App\Exceptions\Filesystem\CredentialsAreMissingException;
use App\Exceptions\Filesystem\VerifyDeviceException;
use App\Services\Filesystem\InstagramCredentialsManager;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use InstagramAPI\Exception\ChallengeRequiredException;
use InstagramAPI\Instagram;

class AuthenticationService implements Authentication
{
    /**
     * @var Instagram
     */
    private $instagram;

    /**
     * @var InstagramCredentialsManager
     */
    private $manager;

    public function __construct(Instagram $instagram, InstagramCredentialsManager $manager)
    {
        $this->instagram = $instagram;
        $this->manager = $manager;
    }

    /**
     * Login to the user's Instagram account.
     *
     * @return \InstagramAPI\Response\LoginResponse|null
     * @throws CredentialsAreMissingException
     * @throws VerifyDeviceException
     * @throws \App\Exceptions\Filesystem\InvalidCredentialStructureException
     */
    public function login()
    {
        if(!$this->manager->exists()){
            throw new CredentialsAreMissingException();
        }

        try{
            $credentials = $this->manager->get();
        }catch (FileNotFoundException $exception){
            throw new CredentialsAreMissingException();
        }

        $username = $credentials['username'];
        $password = $credentials['password'];

        try{
            $login = $this->instagram->login($username, $password);
        }catch (ChallengeRequiredException $exception){
            throw new VerifyDeviceException();
        }

        return $login;
    }
}
