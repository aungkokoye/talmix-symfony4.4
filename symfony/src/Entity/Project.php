<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\ProjectRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
Use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use App\Validator\ContentCheck;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={
 *      "get" = {
 *          "path"="/my-project/{id}",
 *          "normalization_context"={"groups"={"project:detail"}}
 *          },
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
 *
 * @ApiFilter(BooleanFilter::class, properties={"published"})
 * @ApiFilter(SearchFilter::class, properties={"name" : "partial", "description" : "partial"})
 * @ApiFilter(RangeFilter::class, properties={"value"})
 * @ApiFilter(PropertyFilter::class)
 *
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     *
     * @Groups({"project:read", "project:detail"})
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"project:read", "project:write", "project:detail"})
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @ContentCheck() // custom validator
     *
     */
    private $name;

    /**
     * The value of project in pences.
     *
     * @Groups({"project:write", "project:detail"})
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\LessThan(value=100000)
     * @Assert\GreaterThan(value=1)
     */
    private $value;

    /**
     *  @Groups({"project:read", "project:write", "project:detail"})
     *
     * @ORM\ManyToOne(targetEntity=ProjectOwner::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @Groups({"project:read",  "project:write", "project:detail"})
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Callback("App\Entity\Project", "validateDescription")
     */
    private $description;

    /**
     * @Groups({"project:read",  "project:write", "project:detail"})
     * @ORM\Column(type="boolean", options={"default" : 0})
     *
     */
    private $published;

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

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public static function validateDescription($value, ExecutionContextInterface $context)
    {
        /** @var Project $project */
        $project = $context->getObject();

        if ($project->value > 100 && empty($value)) {
            $context->addViolation(
                "Description should not be empty."
            );
        }
    }
}
