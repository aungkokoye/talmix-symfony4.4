<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProjectOwnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      collectionOperations={"get", "post"},
 *      itemOperations={"get", "put", "delete"}
 * )
 * @ORM\Entity(repositoryClass=ProjectOwnerRepository::class)
 *
 *
 * normalizationContext={
 *  "groups"={"project_owner:read"}
 * },
 * denormalizationContext={
 *  "groups"={"project_owner:write"}
 * }
 */
class ProjectOwner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({
     *     "project_owner:read",
     *     "project:detail"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({
     *     "project_owner:read",
     *     "project_owner:write",
     *     "project:write",
     *     "project:detail"
     * })
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="owner", cascade={"remove"})
     *
     * @Groups({"project_owner:read"})
     */
    private $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
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

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setOwner($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getOwner() === $this) {
                $project->setOwner(null);
            }
        }

        return $this;
    }
}
