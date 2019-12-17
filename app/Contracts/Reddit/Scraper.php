<?php


namespace App\Contracts\Reddit;


use App\Services\Reddit\ConfigurationManager;
use GuzzleHttp\Client;
use Illuminate\Contracts\Filesystem\Filesystem;

interface Scraper
{
    public function __construct(Client $client, ConfigurationManager $manager, Filesystem $filesystem);

    /**
     * Get JSON Data from Reddit API
     *
     * @param string $subreddit
     * @param string $sort
     * @return mixed
     */
    public function getJsonData(string $subreddit, string $sort);

    /**
     * Filter JSON content from Reddit for posting
     *
     * @throws \App\Exceptions\InvalidArrayStructureException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return array
     */
    public function filterPosts();
}
