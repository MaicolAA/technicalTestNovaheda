<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Exception;

use App\Http\Request\Company\CreateCompanyRequest;
use App\Http\Request\Company\UpdateCompanyRequest;

use App\Services\CompanyService;

use App\Dto\CompanyDto;



class CompanyController extends MainController
{
    /**
     * @param \App\Services\CompanyService $companyService
     */
    public function __construct(
        private CompanyService $companyService
    ) {}

    
    /**
     * Lista las compañias
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $companies = $this->companyService->getAllCompanies();
            return $this->ok(
                $companies->toArray(),
                __('messages.ok')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Crea una nueva compañia
     * @param \App\Http\Request\Company\CreateCompanyRequest $request
     * @return JsonResponse
     */
    public function store(CreateCompanyRequest $request): JsonResponse
    {
        try {
            $request->validate($request->rules());

            $companyDto = CompanyDto::fromRequest($request->validated());
            $company = $this->companyService->createCompany($companyDto);
            return $this->created(
                $company->toArray(),
                __('messages.company_created')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Presenta el detalle de una compañia
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $company = $this->companyService->getCompany($id);
            if (!$company) {
                throw new Exception(__('messages.company_not_found'), 404);
            }
            return $this->ok(
                $company->toArray(),
                __('messages.ok')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Actualiza una compañia
     * @param \App\Http\Request\Company\UpdateCompanyRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateCompanyRequest $request, int $id): JsonResponse
    {
        try {
            $companyDto = CompanyDto::fromRequest($request->validated())->setId($id);

            $company = $this->companyService->updateCompany( $companyDto);
            if (!$company) {
                throw new Exception(__('messages.company_not_found'), 404);
            }

            return $this->ok(
                $company->toArray(),
                __('messages.company_updated')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Elimina una compañia
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->companyService->deleteCompany($id);
            if (!$deleted) {
                throw new Exception(__('messages.company_not_found'), 404);
            }
            return $this->message(
                __('messages.company_deleted')
            );

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
