<?php


namespace App\Contracts\Filesystem;


use App\Exceptions\Filesystem\InvalidCredentialStructureException;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Filesystem\Filesystem;

interface InstagramCredentials
{
    public function __construct(Filesystem $filesystem, Encrypter $encrypter);

    /**
     * Check if the credentials file exists.
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     * Save a user's Instagram credentials inside an encrypted file within
     * their home directory.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function save(string $username, string $password): bool;

    /**
     * Retrieve a user's credentials.
     *
     * @return array
     * @throws InvalidCredentialStructureException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get(): array;
}
