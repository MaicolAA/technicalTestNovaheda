<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository
{
    public function all(): Collection
    {
        return Company::all();
    }

    public function find(int $id)
    {
        return Company::find($id);
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(Company $company, array $data): Company
    {
        $company->update($data);
        return $company;
    }

    public function delete(Company $company): ?bool 
    {
        return $company->delete();
    }
}