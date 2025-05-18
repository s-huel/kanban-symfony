<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: Lane::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lane $lane = null;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: TagTask::class, cascade: ['persist', 'remove'])]
    private Collection $tagTasks;

    #[ORM\ManyToOne(targetEntity: Priority::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: true)] // Set to true if tasks can have no priority.
    private ?Priority $priority = null;

    public function __construct()
    {
        $this->tagTasks = new ArrayCollection();
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

    public function getLane(): ?Lane
    {
        return $this->lane;
    }

    public function setLane(?Lane $lane): static
    {
        $this->lane = $lane;
        return $this;
    }

    public function getTagTasks(): Collection
    {
        return $this->tagTasks;
    }

    public function addTagTask(TagTask $tagTask): static
    {
        if (!$this->tagTasks->contains($tagTask)) {
            $this->tagTasks->add($tagTask);
            $tagTask->setTask($this);
        }
        return $this;
    }

    public function removeTagTask(TagTask $tagTask): static
    {
        if ($this->tagTasks->removeElement($tagTask) && $tagTask->getTask() === $this) {
            $tagTask->setTask(null);
        }
        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(?Priority $priority): static
    {
        $this->priority = $priority;
        return $this;
    }
}
