<?php

namespace App\DTO;


use App\DTO\Dto;
use App\Models\Company;

class CompanyDto extends Dto
{
    private ?string $name;
    private ?string $email;
    private ?string $address;
    private ?string $website;
    private ?string $description;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): void
    {
        $this->website = $website;
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
            'email' => $this->getEmail(),
            'address' => $this->getAddress(),
            'website' => $this->getWebsite(),
            'description' => $this->getDescription(),
        ];
    }

    public function toUpdate(): array
    {
        $toUpdate = [];

        if ($this->getName()) {
            $toUpdate['name'] = $this->getName();
        }
        if ($this->getEmail()) {
            $toUpdate['email'] = $this->getEmail();
        }
        if ($this->getAddress()) {
            $toUpdate['address'] = $this->getAddress();
        }
        if ($this->getWebsite()) {
            $toUpdate['website'] = $this->getWebsite();
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
            'email' => $this->getEmail(),
            'address' => $this->getAddress(),
            'website' => $this->getWebsite(),
            'description' => $this->getDescription(),
        ];
    }

    public static function fromRequest(array $data): self
    {
        $self = new self();
        $self->setName($data['name'] ?? null);
        $self->setEmail($data['email'] ?? null);
        $self->setAddress($data['address'] ?? null);
        $self->setWebsite($data['website'] ?? null);
        $self->setDescription($data['description'] ?? null);

        return $self;
    }

    public static function fromModel(Company $model): self
    {
        $self = new self();
        $self->setId($model->id);
        $self->setName($model->name);
        $self->setEmail($model->email);
        $self->setAddress($model->address);
        $self->setWebsite($model->website);
        $self->setDescription($model->description);

        return $self;
    }

}