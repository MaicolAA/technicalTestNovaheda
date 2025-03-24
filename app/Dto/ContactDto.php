<?php

namespace App\DTO;

use App\Models\Contact;

class ContactDto extends Dto
{

    public function __construct(
        private ?string $name = null,
        private ?string $email = null,
        private ?string $phone = null,
        private ?string $address = null,
        private ?CompanyDto $company = null
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->company = $company;
    }

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getCompany(): ?CompanyDto
    {
        return $this->company;
    }

    public function setCompany(?CompanyDto $company): void
    {
        $this->company = $company;
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            company: (new CompanyDto())->setId($data['company_id'] ?? null),
        );
    }

    public static function fromModel(Contact $contact, bool $withCompany = true): self
    {

        $self = new self(
            name: $contact->name,
            email: $contact->email,
            phone: $contact->phone,
            address: $contact->address
        );
        $self->setId($contact->id);

        if ($withCompany) {
            $company = new CompanyDto();
            $company->setId($contact->company->id);
            $company->setName($contact->company->name);
            $company->setEmail($contact->company->email);
            $company->setAddress($contact->company->address);
            $company->setWebsite($contact->company->website);
            $company->setDescription($contact->company->description);

            $self->setCompany($company);
        }

        return $self;
    }


    public function toCreate(): array
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'address' => $this->getAddress(),
            'company_id' => $this->getCompany()->getId(),
        ];
    }

    public function toUpdate(): array
    {
        $toUpdate = [];

        if ($this->name) {
            $toUpdate['name'] = $this->getName();
        }
        if ($this->email) {
            $toUpdate['email'] = $this->getEmail();
        }
        if ($this->phone) {
            $toUpdate['phone'] = $this->getPhone();
        }
        if ($this->address) {
            $toUpdate['address'] = $this->getAddress();
        }
        if ($this->getCompany()->getId()) {
            $toUpdate['company_id'] = $this->getCompany()->getId();
        }

        return $toUpdate;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'address' => $this->getAddress(),
            'company' => $this->getCompany()->toArray(),
        ];
    }

    public function toList(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'address' => $this->getAddress(),
        ];
    }
}
