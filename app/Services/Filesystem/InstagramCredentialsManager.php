<?php


namespace App\Services\Filesystem;


use App\Contracts\Filesystem\InstagramCredentials;
use App\Exceptions\Filesystem\InvalidCredentialStructureException;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Class InstagramCredentialsManager
 * @package App\Services\Filesystem
 * @deprecated
 */
class InstagramCredentialsManager implements InstagramCredentials
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
     * Check if the credentials file exists.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->filesystem->exists(config('filesystems.path.credentials'));
    }

    /**
     * Save a user's Instagram credentials inside an encrypted file within
     * their home directory.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function save(string $username, string $password): bool
    {
        $credentials = [
            'username' =>  $username,
            'password' => $password
        ];
        $credentials = json_encode($credentials);
        $file = $this->encrypter->encrypt($credentials);
        return $this->filesystem->put(config('filesystems.path.credentials'), $file);
    }

    /**
     * Retrieve a user's credentials.
     *
     * @return array
     * @throws InvalidCredentialStructureException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get(): array
    {
        $file = $this->filesystem->get(config('filesystems.path.credentials'));
        $credentials = $this->encrypter->decrypt($file);
        $credentials = json_decode($credentials, true);
        if(!isset($credentials['username']) || !isset($credentials['password'])){
            throw new InvalidCredentialStructureException();
        }
        return $credentials;
    }
}
