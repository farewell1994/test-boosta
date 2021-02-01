<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Expression(
     *     "this.checkAuthor()",
     *     message="article.required_field"
     * )
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Expression(
     *     "this.checkTitle()",
     *     message="article.required_field"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $fromFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $edited;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->edited = new \DateTime();
        $this->fromFile = false;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string|null $author
     * @return $this
     */
    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return $this
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFromFile(): bool
    {
        return $this->fromFile;
    }

    /**
     * @param bool $fromFile
     * @return Article
     */
    public function setFromFile(bool $fromFile): Article
    {
        $this->fromFile = $fromFile;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreated(): \DateTimeInterface
    {
        return $this->created;
    }

    /**
     * @param \DateTimeInterface $created
     * @return $this
     */
    public function setCreated(\DateTimeInterface $created): Article
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getEdited(): \DateTimeInterface
    {
        return $this->edited;
    }

    /**
     * @param \DateTimeInterface $edited
     * @return Article
     */
    public function setEdited(\DateTimeInterface $edited): Article
    {
        $this->edited = $edited;

        return $this;
    }

    /**
     * @return bool
     */
    public function checkTitle(): bool
    {
        return (!$this->isFromFile() && $this->getTitle()) || $this->isFromFile();
    }

    /**
     * @return bool
     */
    public function checkAuthor(): bool
    {
        return (!$this->isFromFile() && $this->getAuthor()) || $this->isFromFile();
    }
}
