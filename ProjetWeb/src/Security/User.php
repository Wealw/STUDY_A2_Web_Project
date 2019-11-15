<?php

namespace App\Security;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package App\Security
 * @Vich\Uploadable
 */
class User implements UserInterface
{

    private $user_id;
    private $user_mail;
    private $user_first_name;
    private $user_last_name;
    private $user_phone;
    private $user_postal_code;
    private $user_address;
    private $user_city;

    /**
     * @Assert\EqualTo(propertyPath="confirm_password", message="La confirmation du mot de passe n'est pas bonne")
     */
    private $user_password;
    private $confirm_password;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="user_image_path")
     */
    private $imageFile;

    private $user_image_path;
    private $created_at;
    private $modified_at;
    private $center_id;
    private $role_id;
    private $center_name;
    private $roles;
    private $token = null;

    /**
     * Creates a new instance from a given JWT payload.
     *
     * @param string $username
     * @param array $payload
     *
     * @return User
     */
    public static function createFromPayload($username, array $payload)
    {
        return new self(
            $payload['user_id'],
            $payload['user_first_name'],
            $payload['user_last_name'],
            $username,
            $payload['user_phone'],
            $payload['user_postal_code'],
            $payload['user_address'],
            $payload['user_city'],
            $payload['user_password'],
            $payload['user_image_path'],
            $payload['created_at'],
            $payload['modified_at'],
            $payload['center_id'],
            $payload['role_id']
        );
    }

    public function __construct($user_id, $user_first_name, $user_last_name, $user_mail, $user_phone, $user_postal_code, $user_address, $user_city, $user_password, $user_image_path, $created_at, $modified_at, $center_id, $role_id)
    {
        $this->user_id = $user_id;
        $this->user_first_name = $user_first_name;
        $this->user_last_name = $user_last_name;
        $this->user_mail = $user_mail;
        $this->user_phone = $user_phone;
        $this->user_postal_code = $user_postal_code;
        $this->user_address = $user_address;
        $this->user_city = $user_city;
        $this->user_password = $user_password;
        $this->user_image_path = $user_image_path;
        $this->created_at = $created_at;
        $this->modified_at = $modified_at;
        $this->center_id = $center_id;
        $this->role_id = $role_id;
        $client = new Client();
        $response = $client->request('GET', 'http://127.0.0.1:3000/api/centers/' . $center_id);
        $datas = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->center_name = $datas['center_location'];
        $response = $client->request('GET', 'http://127.0.0.1:3000/api/roles/' . $role_id);
        $datas = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->roles[] = $datas['role_name'];
    }

    /**
     * @return null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param null $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return User
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserMail()
    {
        return $this->user_mail;
    }

    /**
     * @param mixed $user_mail
     * @return User
     */
    public function setUserMail($user_mail)
    {
        $this->user_mail = $user_mail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserFirstName()
    {
        return $this->user_first_name;
    }

    /**
     * @param mixed $user_first_name
     * @return User
     */
    public function setUserFirstName($user_first_name)
    {
        $this->user_first_name = $user_first_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserLastName()
    {
        return $this->user_last_name;
    }

    /**
     * @param mixed $user_last_name
     * @return User
     */
    public function setUserLastName($user_last_name)
    {
        $this->user_last_name = $user_last_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserPhone()
    {
        return $this->user_phone;
    }

    /**
     * @param mixed $user_phone
     * @return User
     */
    public function setUserPhone($user_phone)
    {
        $this->user_phone = $user_phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserPostalCode()
    {
        return $this->user_postal_code;
    }

    /**
     * @param mixed $user_postal_code
     * @return User
     */
    public function setUserPostalCode($user_postal_code)
    {
        $this->user_postal_code = $user_postal_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserAddress()
    {
        return $this->user_address;
    }

    /**
     * @param mixed $user_address
     * @return User
     */
    public function setUserAddress($user_address)
    {
        $this->user_address = $user_address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserCity()
    {
        return $this->user_city;
    }

    /**
     * @param mixed $user_city
     * @return User
     */
    public function setUserCity($user_city)
    {
        $this->user_city = $user_city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserPassword()
    {
        return $this->user_password;
    }

    /**
     * @param mixed $user_password
     * @return User
     */
    public function setUserPassword($user_password)
    {
        $this->user_password = $user_password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserImagePath()
    {
        return $this->user_image_path;
    }

    /**
     * @param mixed $user_image_path
     * @return User
     */
    public function setUserImagePath($user_image_path)
    {
        $this->user_image_path = $user_image_path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     * @return User
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * @param mixed $modified_at
     * @return User
     */
    public function setModifiedAt($modified_at)
    {
        $this->modified_at = $modified_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCenterId()
    {
        return $this->center_id;
    }

    /**
     * @param mixed $center_id
     * @return User
     */
    public function setCenterId($center_id)
    {
        $this->center_id = $center_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * @param mixed $role_id
     * @return User
     */
    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCenterName()
    {
        return $this->center_name;
    }

    /**
     * @param mixed $center_name
     * @return User
     */
    public function setCenterName($center_name)
    {
        $this->center_name = $center_name;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->user_mail;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->user_password;
    }

    /**
     * @return mixed
     */
    public function getConfirmPassword()
    {
        return $this->confirm_password;
    }

    /**
     * @param mixed $confirm_password
     * @return User
     */
    public function setConfirmPassword($confirm_password)
    {
        $this->confirm_password = $confirm_password;
        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        return 8;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return User
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): User
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->event_modified_at = new \DateTime('now');
        }
        return $this;
    }

}
