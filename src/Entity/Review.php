<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private string $comment;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private int $rating;

    #[ORM\OneToMany(mappedBy: 'review', targetEntity: Image::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'comment' => $this->getComment(),
            'date' => $this->getDate()->format('Y-m-d'),
            'rating' => $this->getRating(),
            'images' => $this->images->map(static function(Image $image) {
                return $image->toArray();
            })
        ];
    }

    public static function fromArray(array $data): Review
    {
        $review = new Review();
        $review->setComment($data['comment'] ?? null);
        $review->setEmail($data['email'] ?? null);
        $review->setDate(new DateTime());
        $review->setRating($data['rating'] ?? null);

        foreach ($data['images'] ?? [] as $imageData) {
            $review->addImage(Image::fromArray($imageData));
        }

        return $review;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setReview($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getReview() === $this) {
                $image->setReview(null);
            }
        }

        return $this;
    }
}
