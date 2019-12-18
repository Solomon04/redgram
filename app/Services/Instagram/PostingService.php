<?php


namespace App\Services\Instagram;


use InstagramAPI\Instagram;
use InstagramAPI\Media\Photo\InstagramPhoto;
use InstagramAPI\Media\Video\InstagramVideo;

class PostingService
{
    /**
     * @var Instagram
     */
    private $instagram;

    public function __construct(Instagram $instagram)
    {
        $this->instagram = $instagram;
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
    public function submit(string $file, string $caption, $isVideo = false)
    {
        if($isVideo){
            $video = new InstagramVideo($file);
            return $this->instagram->timeline->uploadVideo($video->getFile(), ['caption' => $caption]);
        }
        $photo = new InstagramPhoto($file);
        return $this->instagram->timeline->uploadPhoto($photo->getFile(), ['caption' => $caption]);
    }
}
