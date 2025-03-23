<?php

namespace App\DTO;

use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class UserDTO extends Dto
{
    private ?string $name;
    private string $email;
    private string $password;

    private DateTime $created_at;
    private ?DateTime $updated_at;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromRequest(Request $request): self
    {
        $self = new self();
        $self->setName($request->input('name'));
        $self->setEmail($request->input('email'));
        $self->setPassword($request->input('password'));
        return $self;
    }

    public static function fromModel(User $user): self
    {
        $self = new self();
        $self->setId($user->id);
        $self->setName($user->name);
        $self->setEmail($user->email);
        $self->setCreatedAt($user->created_at);
        $self->setUpdatedAt($user->updated_at);
        return $self;
    }

}