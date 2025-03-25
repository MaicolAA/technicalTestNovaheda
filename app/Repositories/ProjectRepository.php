<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Models\Project;

class ProjectRepository
{

    public function all(): Collection
    {
        return Project::all();
    }

    public function find(int $id)
    {
        return Project::find($id);
    }

    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function update(Project $project, array $data): Project
    {
        $project->update($data);
        return $project;
    }

    public function delete(Project $project): bool
    {
        return $project->delete();
    }
}
