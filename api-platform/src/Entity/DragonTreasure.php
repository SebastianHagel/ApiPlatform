<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\DragonTreasureRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: DragonTreasureRepository::class)]
#[ApiResource(
  shortName: 'Treasure',
  description: 'A rare and valuable treasure.',
  operations: [
    new Get(
      normalizationContext: ['groups' => ['treasure:read', 'treasure:item:get']]
    ),
    new GetCollection(),
    new Post(),
    new Put(),
    new Patch(),
    new Delete(),
  ],
  formats: [
      'jsonld',
      'jsonhal',
      'csv' => 'text/csv',
  ],
  normalizationContext: [
    'groups' => ['treasure:read']
  ],
  denormalizationContext: [
    'groups' => ['treasure:write']
  ],
  paginationItemsPerPage: 10,
)]
#[ApiResource(
  uriTemplate: '/users/{user_id}/treasures.{_format}',
  shortName: 'Treasure',
  operations: [new GetCollection()],
  uriVariables: [
    'user_id' => new Link(
      fromProperty: 'dragonTreasures',
      fromClass: User::class,
    )
  ],
  normalizationContext: [
    'groups' => ['treasure:read']
  ],
  denormalizationContext: [
    'groups' => ['treasure:write']
  ],
)]

#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(SearchFilter::class, properties: [
    'owner.username' => 'partial',
])]
class DragonTreasure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['treasure:read', 'treasure:write', 'user:read', 'user:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial' )]
    #[Assert\NotBlank]
    #[Assert\Length(min:2, max:50, maxMessage: 'Describe your loot in 50 characters or less.')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['treasure:read', 'treasure:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial' )]
    #[Assert\NotBlank]
    private ?string $description = null;

    /**
     * The estimated value of this treasure, in gold coins.
     */
    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write', 'user:read', 'user:write'])]
    #[ApiFilter(RangeFilter::class)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $value = 0;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\LessThanOrEqual(10000000)]
    private ?int $coolFactor = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $plunderedAt;

    #[ORM\Column]
    #[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
    private ?bool $isPublished = false;

    #[ORM\ManyToOne(inversedBy: 'dragonTreasures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['treasure:read', 'treasure:write'])]
    #[Assert\Valid]
    private ?User $owner = null;

    public function __construct(string $name = null)
    {
        $this->plunderedAt = new \DateTimeImmutable();
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Groups(['treasure:read'])]
    public function getShortDescription(): ?string
    {
        return u($this->description)->truncate(40, '...');
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    #[Groups(['treasure:write', 'user:write'])]
    #[SerializedName('description')]
    public function setTextDescription(string $description): static
    {
        $this->description = nl2br($description);

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getCoolFactor(): ?int
    {
        return $this->coolFactor;
    }

    public function setCoolFactor(int $coolFactor): static
    {
        $this->coolFactor = $coolFactor;

        return $this;
    }

    public function getPlunderedAt(): ?\DateTimeImmutable
    {
        return $this->plunderedAt;
    }

    public function setPlunderedAt(?\DateTimeImmutable $plunderedAt): self
    {
       $this->plunderedAt = $plunderedAt;

       return $this;
    }

    /**
     * A human-readable representation of when this treasure was plundered.
     *
     * @return \DateTimeImmutable|null
     */
    #[Groups(['treasure:read'])]
    public function getPlunderedAtAgo(): string
    {
        return Carbon::instance($this->plunderedAt)->diffForHumans();
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
