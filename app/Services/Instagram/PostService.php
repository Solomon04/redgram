<?php


namespace App\Services\Instagram;


use App\Contracts\Instagram\Post;
use App\Exceptions\Filesystem\CredentialsAreMissingException;
use App\Exceptions\Filesystem\VerifyDeviceException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use InstagramAPI\Exception\ChallengeRequiredException;
use InstagramAPI\Instagram;
use InstagramAPI\Media\Photo\InstagramPhoto;
use InstagramAPI\Media\Video\InstagramVideo;

class PostService implements Post
{
    /**
     * @var Instagram
     */
    private $instagram;

    /**
     * @var CredentialsManger
     */
    private $manager;

    public function __construct(Instagram $instagram, CredentialsManger $manager)
    {
        $this->instagram = $instagram;
        $this->manager = $manager;
    }

    /**
     * Login to the user's Instagram account.
     *
     * @return $this
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
            $this->instagram->login($username, $password);
        }catch (ChallengeRequiredException $exception){
            throw new VerifyDeviceException();
        }

        return $this;
    }

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
    public function post(string $file, string $caption, bool $isVideo = false)
    {
        if($isVideo){
            $video = new InstagramVideo($file);
            return $this->instagram->timeline->uploadVideo($video->getFile(), ['caption' => $caption]);
        }
        $photo = new InstagramPhoto($file);
        return $this->instagram->timeline->uploadPhoto($photo->getFile(), ['caption' => $caption]);
    }
}
