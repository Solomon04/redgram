<?php


namespace App\Services\Reddit;


use App\Contracts\Reddit\Scraper;
use App\Services\Filesystem\ConfigurationManager;
use GuzzleHttp\Client;
use Illuminate\Contracts\Filesystem\Filesystem;

class ScraperService implements Scraper
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ConfigurationManager
     */
    private $manager;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Client $client, ConfigurationManager $manager, Filesystem $filesystem)
    {
        $this->client = $client;
        $this->manager = $manager;
        $this->filesystem = $filesystem;
    }

    /**
     *
     *
     * @throws \App\Exceptions\InvalidArrayStructureException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getPosts()
    {
        $config = $this->manager->get();
        $json = $this->getJsonData($config['subreddit'], $config['sort']);
        $posts = $json->data->children;
        foreach ($posts as $post){
            if (!strpos($post->data->url, 'jpg')) {
                continue;
            }

            if($post->data->score < $config['minScore']){
                continue;
            }

            if($this->filesystem->exists($post->data->id . ".jpg")){
                continue;
            }

            $image = file_get_contents($post->data->url);

            $path = config('filesystems.path.posts') . DIRECTORY_SEPARATOR . $post->data->id . ".jpg";
            $this->filesystem->put($path, $image);

        }
    }

    /**
     * Get JSON Data from Reddit API
     *
     * @param string $subreddit
     * @param string $sort
     * @return mixed
     */
    private function getJsonData(string $subreddit, string $sort)
    {
        $response = $this->client->get("r/{$subreddit}/new/.json", [
            'headers' => [
                'User-Agent' => config('app.name'),
            ],
            'query' => [
                "sort" => $sort,
                'limit' => 100
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }
}
