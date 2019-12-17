<?php


namespace App\Services\Reddit;


use App\Contracts\Reddit\Configuration;
use App\Exceptions\InvalidArrayStructureException;
use Illuminate\Contracts\Filesystem\Filesystem;

class ConfigurationManager implements Configuration
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Check if Reddit file exists.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->filesystem->exists(config('filesystems.path.reddit'));
    }

    /**
     * Save Reddit configuration values.
     *
     * @param string $subreddit
     * @param string $sort
     * @param int $minScore
     * @param bool $allowVideos
     * @return bool
     */
    public function save(string $subreddit, string $sort, int $minScore, bool $allowVideos): bool
    {
        $config = [
            'subreddit' => $subreddit,
            'sort' => $sort,
            'minScore' => $minScore,
            'allowVideos' => $allowVideos
        ];
        $file = json_encode($config);
        return $this->filesystem->put(config('filesystems.path.reddit'), $file);
    }

    /**
     * Get Reddit configuration values.
     *
     * @return array
     * @throws InvalidArrayStructureException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get(): array
    {
        $file = $this->filesystem->get(config('filesystems.path.reddit'));
        $config = json_decode($file, true);
        if(!isset($config['subreddit']) || !isset($config['sort'])){
            throw new InvalidArrayStructureException();
        }

        return $config;
    }
}
