<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="books")
 * @ORM\HasLifecycleCallbacks()
 */
class Book extends DateRecordingEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=150)
     */
    private string $name;

    /**
     * @var Collection<Author>
     * @ORM\ManyToMany(targetEntity="\App\Entity\Author")
     * @ORM\JoinTable(name="authors_books",
     *     joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="author_id", referencedColumnName="id")}
     * )
     */
    private Collection $authors;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->authors = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): void
    {
        $this->authors->add($author);
    }

    public function removeAuthor(Author $author): void
    {
        $this->authors->removeElement($author);
    }
}
