<?php

namespace App\Security;


use GuzzleHttp\Client;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->fetchUser($username);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $username = $user->getUsername();

        return $this->fetchUser($username);
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }

    private function fetchUser($username)
    {
        $client = new Client();
        $response = $client->request('GET', 'http://127.0.0.1:3000/api/users');
        $datas = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        foreach ($datas as $data) {
            if ($data['user_email'] = $username) {
                return User::createFromPayload($username, $data);
            }
        }
        return null;
    }
}