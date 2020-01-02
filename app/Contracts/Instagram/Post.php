<?php


namespace App\Contracts\Instagram;


use App\Exceptions\Filesystem\CredentialsAreMissingException;
use App\Exceptions\Filesystem\VerifyDeviceException;
use App\Services\Instagram\CredentialsManger;
use InstagramAPI\Instagram;

interface Post
{
    /**
     * Post constructor.
     * @param Instagram $instagram
     * @param CredentialsManger $manager
     */
    public function __construct(Instagram $instagram, CredentialsManger $manager);

    /**
     * Login to the user's Instagram account.
     *
     * @return $this
     * @throws CredentialsAreMissingException
     * @throws VerifyDeviceException
     * @throws \App\Exceptions\Filesystem\InvalidCredentialStructureException
     */
    public function login();

    /**
     * Post content to Instagram feed. Can be either
     * a photo or video.
     *
     * @param string $file
     * @param string $caption
     * @param bool $isVideo
     * @return \InstagramAPI\Response\ConfigureResponse
     * @throws \Exception
     */
    public function post(string $file, string $caption, bool $isVideo = false);
}
