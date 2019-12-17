<?php
return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => getenv('HOME') . DIRECTORY_SEPARATOR .'redgram',
        ],
    ],
    'path' => [
        'credentials' => 'credentials.txt',
        'reddit' => 'reddit.json',
        'posts' => 'posts',
        'caption' => 'caption.txt'
    ]
];
