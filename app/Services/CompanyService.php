<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Exception;

use App\DTO\CompanyDto;
use App\Repositories\CompanyRepository;

class CompanyService
{
    public function __construct(
        private CompanyRepository $companyRepository
    ) {}

    public function createCompany(CompanyDto $companyDto): CompanyDto
    {
        $company =  $this->companyRepository->create($companyDto->toCreate());
        return CompanyDto::fromModel($company);
    }

    public function updateCompany(CompanyDto $companyDto): ?CompanyDto
    {
        $company = $this->companyRepository->find($companyDto->getId());
        if (!$company) {
            throw new Exception(__('messages.company_not_found'), 404);
        }
        $company = $this->companyRepository->update($company, $companyDto->toUpdate());

        return CompanyDto::fromModel($company);
    }

    public function deleteCompany(int $id): bool
    {
        $company = $this->companyRepository->find($id);
        if (!$company) {
            throw new Exception(__('messages.company_not_found'), 404);
        }
        return $this->companyRepository->delete($company);
    }

    public function getCompany(int $id): ?CompanyDto
    {
        $company = $this->companyRepository->find($id);
        if (!$company) {
            return null;
        }
        return CompanyDto::fromModel($company);
    }



    public function getAllCompanies(): Collection
    {
        $companies = $this->companyRepository->all();
        return  $companies->map(function ($company): CompanyDto {
            return CompanyDto::fromModel($company);
        });
    }
}
