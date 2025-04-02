<?php

namespace App\Entity;

use App\Repository\LaneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LaneRepository::class)]
class Lane
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'lane')]
    private Collection $item;

    public function __construct()
    {
        $this->item = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getItem(): Collection
    {
        return $this->item;
    }

    public function addItem(Task $item): static
    {
        if (!$this->item->contains($item)) {
            $this->item->add($item);
            $item->setLane($this);
        }

        return $this;
    }

    public function removeItem(Task $item): static
    {
        if ($this->item->removeElement($item)) {
            if ($item->getLane() === $this) {
                $item->setLane(null);
            }
        }

        return $this;
    }
}
