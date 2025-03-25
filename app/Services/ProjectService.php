<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Exception;

use App\Dto\ProjectDto;
use App\Repositories\ProjectRepository;

class ProjectService
{
    public function __construct(
        private ProjectRepository $projectRepository
    ) {}

    public function createProject(ProjectDto $projectDto): ProjectDto
    {
        $project =  $this->projectRepository->create($projectDto->toCreate());
        return ProjectDto::fromModel($project);
    }

    public function updateProject(ProjectDto $projectDto): ?ProjectDto
    {
        $project = $this->projectRepository->find($projectDto->getId());
        if (!$project) {
            throw new Exception(__('messages.project_not_found'), 404);
        }
        $project = $this->projectRepository->update($project, $projectDto->toUpdate());

        return ProjectDto::fromModel($project);
    }

    public function deleteProject(int $id): bool
    {
        $project = $this->projectRepository->find($id);
        if (!$project) {
            throw new Exception(__('messages.project_not_found'), 404);
        }
        return $this->projectRepository->delete($project);
    }

    public function getProject(int $id): ?ProjectDto
    {
        $project = $this->projectRepository->find($id);
        if (!$project) {
            return null;
        }
        return ProjectDto::fromModel($project);
    }



    public function getAllProjects(): Collection
    {
        $projects = $this->projectRepository->all();
        return  $projects->map(function ($project): ProjectDto {
            return ProjectDto::fromModel($project);
        });
    }
}
