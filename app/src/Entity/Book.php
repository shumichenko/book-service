<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Translator;

/**
 * @ORM\Entity()
 * @ORM\Table(name="books")
 * @Translator\TranslationEntity(class="\App\Entity\BookTranslation")
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
     * @var Collection<Author>
     * @ORM\ManyToMany(targetEntity="\App\Entity\Author")
     * @ORM\JoinTable(name="authors_books",
     *     joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="author_id", referencedColumnName="id")}
     * )
     */
    private Collection $authors;

    /**
     * @ORM\OneToMany(
     *   targetEntity="\App\Entity\BookTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private Collection $translations;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function getTranslation(string $language, string $field): ?BookTranslation
    {
        $collection = $this->translations->filter(static function (BookTranslation $translation) use ($language, $field) {
            return $translation->getLocale() === $language && $translation->getField() === $field;
        });

        return $collection->first() ?: null;
    }

    public function addTranslation(BookTranslation $translation): void
    {
        if ($this->getTranslation($translation->getLocale(), $translation->getField())) {
            return;
        }
        $this->translations[] = $translation;
        $translation->setObject($this);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): void
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
        }
    }

    public function removeAuthor(Author $author): void
    {
        $this->authors->removeElement($author);
    }
}
