<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="authors")
 * @ORM\HasLifecycleCallbacks()
 */
class Author extends DateRecordingEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=100)
     */
    private string $name;

    /**
     * @var Collection<Book>
     * @ORM\ManyToMany(targetEntity="\App\Entity\Book", mappedBy="books")
     */
    private Collection $books;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->books = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }
}
