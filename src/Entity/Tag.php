<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'tag', targetEntity: TagTask::class, cascade: ['persist', 'remove'])]
    private Collection $tagTasks;

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

    public function getTagTasks(): Collection
    {
        return $this->tagTasks;
    }

    public function addTagTask(TagTask $tagTask): static
    {
        if (!$this->tagTasks->contains($tagTask)) {
            $this->tagTasks->add($tagTask);
            $tagTask->setTag($this);
        }
        return $this;
    }

    public function removeTagTask(TagTask $tagTask): static
    {
        if ($this->tagTasks->removeElement($tagTask)) {
            if ($tagTask->getTag() === $this) {
                $tagTask->setTag(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, TagTask>
     */
    public function getTask(): Collection
    {
        return $this->task;
    }

    public function addTask(TagTask $task): static
    {
        if (!$this->task->contains($task)) {
            $this->task->add($task);
            $task->setTag($this);
        }

        return $this;
    }

    public function removeTask(TagTask $task): static
    {
        if ($this->task->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getTag() === $this) {
                $task->setTag(null);
            }
        }

        return $this;
    }
}
