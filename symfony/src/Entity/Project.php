<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
Use Symfony\Component\Serializer\Annotation\Groups;
Use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={
 *     "get" = {"path"="/my-project/{id}"},
 *      "put"
 *     },
 *     normalizationContext={
 *      "groups"={"project:read"}
 *     },
 *     denormalizationContext={
 *       "groups"={"project:write"}
 *     }
 *
 * )
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     *
     * @Groups({"project:read"})
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"project:read", "project:write"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * The value of project in pences.
     *
     *  @Groups({"project:read", "project:write"})
     *
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     *  @Groups({"project:read", "project:write"})
     *
     * @ORM\ManyToOne(targetEntity=ProjectOwner::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @Groups({"project:read",  "project:write"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct(string $description = null)
    {
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getOwner(): ?ProjectOwner
    {
        return $this->owner;
    }

    public function setOwner(?ProjectOwner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

//    prevent overwrite in edit action, property is set in constructor method for create action
//    public function setDescription(?string $description): self
//    {
//        $this->description = $description;
//
//        return $this;
//    }

    /**
     * @SerializedName("decription_upper")
     * @Groups({"project:read"})
     *
     * @return ?string
     */
    public function getDescriptionCapital(): ?string
    {
        return strtoupper($this->description);
    }
}
