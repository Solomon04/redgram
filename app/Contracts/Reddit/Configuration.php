<?php


namespace App\Contracts\Reddit;


use App\Exceptions\InvalidArrayStructureException;
use Illuminate\Contracts\Filesystem\Filesystem;

interface Configuration
{
    public function __construct(Filesystem $filesystem);

    /**
     * Check if Reddit file exists.
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     * Save Reddit configuration values.
     *
     * @param string $subreddit
     * @param string $sort
     * @param int $minScore
     * @param bool $allowVideos
     * @return bool
     */
    public function save(string $subreddit, string $sort, int $minScore, bool $allowVideos): bool;

    /**
     * Get Reddit configuration values.
     *
     * @return array
     * @throws InvalidArrayStructureException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get(): array;
}
