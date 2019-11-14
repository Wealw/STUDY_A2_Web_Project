<?php


namespace App\Entity\Social\Admin;

use Symfony\Component\Validator\Constraints as Assert;

class AdminEventSearch
{

    /**
     * @var string
     * @Assert\Length(max="50")
     */
    private $search;

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param string $search
     */
    public function setSearch(string $search): void
    {
        $this->search = $search;
    }

}