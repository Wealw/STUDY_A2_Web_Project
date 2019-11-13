<?php

namespace App\Security;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $service;
    private $em;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {


        $this->serializer = $serializer;
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function loadUserByUsername($username)
    {
        // Try service
        $client = new CurlHttpClient();
        $response = $client->request('GET', 'http://127.0.0.1/api/users');
        $datas = $this->serializer->deserialize($response->getContent(), User::class, 'json');
        dd($datas);
        $isExisting = false;
        foreach ($datas as $data) {
            if ($data->getUsername === $username) {
                $isExisting = true;
            }
        }
        if (!$isExisting) {
            throw new UsernameNotFoundException(sprintf('No record found for user %s', $username));
        }
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'App\Security\User';
    }


}