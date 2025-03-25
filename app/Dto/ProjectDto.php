<?php

namespace App\Dto;

use App\Dto\Dto;
use App\Models\Project;

class ProjectDto extends Dto
{
    private ?string $name;
    private ?string $description;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function toCreate(): array
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
        ];
    }

    public function toUpdate(): array
    {
        $toUpdate = [];

        if ($this->getName()) {
            $toUpdate['name'] = $this->getName();
        }
     
        if ($this->getDescription()) {
            $toUpdate['description'] = $this->getDescription();
        }

        return $toUpdate;
    }
    

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
        ];
    }

    public static function fromRequest(array $data): self
    {
        $self = new self();
        $self->setName($data['name'] ?? null);
        $self->setDescription($data['description'] ?? null);

        return $self;
    }

    public static function fromModel(Project $model): self
    {
        $self = new self();
        $self->setId($model->id);
        $self->setName($model->name);
        $self->setDescription($model->description);

        return $self;
    }
}