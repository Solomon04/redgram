<?php


namespace App\Services\Reddit;


use App\Contracts\Reddit\Scraper;
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
     * Filter JSON content from Reddit for posting
     *
     * @throws \App\Exceptions\InvalidArrayStructureException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return array
     */
    public function filterPosts()
    {
        $config = $this->manager->get();
        $json = $this->getJsonData($config['subreddit'], $config['sort']);
        $posts = [];
        foreach ($json->data->children as $post){
            if (!strpos($post->data->url, 'jpg')) {
                continue;
            }

            if($post->data->score < $config['minScore']){
                continue;
            }

            if($this->filesystem->exists(config('filesystems.path.posted') . DIRECTORY_SEPARATOR . $post->data->id . '.jpg')){
                continue;
            }
            $posts[] = [
                'id' => $post->data->id,
                'score' => $post->data->score,
                'image' => $post->data->url
            ];
        }
        $score = array_column($posts, 'score');
        array_multisort($score, SORT_DESC, $posts);
        return $posts;
    }

    /**
     * Get JSON Data from Reddit API
     *
     * @param string $subreddit
     * @param string $sort
     * @return mixed
     */
    public function getJsonData(string $subreddit, string $sort)
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
