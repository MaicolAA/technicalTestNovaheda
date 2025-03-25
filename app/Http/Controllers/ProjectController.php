<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Exception;

use App\Services\ProjectService;

use App\Dto\ProjectDto;

use App\Http\Request\Project\CreateProjectRequest;
use App\Http\Request\Project\UpdateProjectRequest;



class ProjectController extends MainController
{
    
    public function __construct(
        private ProjectService $projectService
    ) {}

    
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $projects = $this->projectService->getAllProjects();
            return $this->ok(
                $projects->toArray(),
                __('messages.ok')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * @return JsonResponse
     */
    public function store(CreateProjectRequest $request): JsonResponse
    {
        try {
            $request->validate($request->rules());

            $projectDto = ProjectDto::fromRequest($request->validated());
            $company = $this->projectService->createProject($projectDto);
            return $this->created(
                $company->toArray(),
                __('messages.project_created')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $company = $this->projectService->getProject($id);
            if (!$company) {
                throw new Exception(__('messages.project_not_found'), 404);
            }
            return $this->ok(
                $company->toArray(),
                __('messages.ok')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }


    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        try {
            $projectDto = ProjectDto::fromRequest($request->validated())->setId($id);

            $project = $this->projectService->updateProject( $projectDto);
            if (!$project) {
                throw new Exception(__('messages.project_not_found'), 404);
            }

            return $this->ok(
                $project->toArray(),
                __('messages.project_updated')
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->projectService->deleteProject($id);
            if (!$deleted) {
                throw new Exception(__('messages.project_not_found'), 404);
            }
            return $this->message(
                __('messages.project_deleted')
            );

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
