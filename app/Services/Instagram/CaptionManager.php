<?php


namespace App\Services\Instagram;


use App\Contracts\Instagram\Caption;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Filesystem\Filesystem;
use function GuzzleHttp\Psr7\str;

class CaptionManager implements Caption
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Encrypter
     */
    private $encrypter;

    public function __construct(Filesystem $filesystem, Encrypter $encrypter)
    {
        $this->filesystem = $filesystem;
        $this->encrypter = $encrypter;
    }

    /**
     * Check if the caption file exists.
     *
     * @return bool
     */
    public function exists()
    {
        return $this->filesystem->exists(config('filesystems.path.caption'));
    }

    /**
     * Save a default caption for posting to Instagram
     *
     * @param string $username
     * @param string $phrase
     * @param string $hashtags
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function save(string $username, string $phrase, string $hashtags)
    {
        $body = fopen(base_path('skeleton.txt'), 'r');
        $body = str_replace(':username', $username, $body);
        $body = str_replace(':phrase', $phrase, $body);
        $body = str_replace(':hashtags', $hashtags, $body);
        return $this->filesystem->put(config('filesystems.path.caption'), $body);
    }

    /**
     * Get the default caption
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get()
    {
        return $this->filesystem->get(config('filesystems.path.caption'));
    }
}
