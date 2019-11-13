<?php


namespace App\Entity\Merch;


use Doctrine\Common\Collections\ArrayCollection;

class ConnectSearch
{
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     * @return ConnectSearch
     */
    public function setId(int $id): ConnectSearch
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


}