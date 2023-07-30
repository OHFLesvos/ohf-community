<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreProject;
use App\Http\Resources\Accounting\Project as ProjectResource;
use App\Models\Accounting\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class);
    }

    public function index(Request $request)
    {
        $request->validate([
            'filter' => [
                'nullable',
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'pageSize' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'sortBy' => [
                'nullable',
                'alpha_dash',
                'filled',
                Rule::in([
                    'name',
                    'description',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        $sortBy = $request->input('sortBy', 'name');
        $sortDirection = $request->input('sortDirection', 'asc');
        $pageSize = $request->input('pageSize', 25);
        $filter = trim($request->input('filter', ''));

        return ProjectResource::collection(Project::query()
            ->orderBy($sortBy, $sortDirection)
            ->when(filled($filter), fn (Builder $query) => $this->filterQuery($query, $filter))
            ->paginate($pageSize));
    }

    private function filterQuery(Builder $query, string $filter): Builder
    {
        return $query->where(
            fn (Builder $wq) => $wq
                ->where('name', 'LIKE', '%'.$filter.'%')
                ->orWhere('description', 'LIKE', '%'.$filter.'%')
        );
    }

    public function store(StoreProject $request): JsonResource
    {
        $project = new Project();
        $project->fill($request->all());
        if ($request->parent_id != null) {
            $project->parent()->associate($request->parent_id);
        } else {
            $project->parent()->disassociate();
        }
        $project->save();

        return new ProjectResource($project);
    }

    public function show(Project $project): JsonResource
    {
        return new ProjectResource($project);
    }

    public function update(StoreProject $request, Project $project): JsonResource
    {
        $project->fill($request->all());
        if ($request->parent_id != null) {
            $project->parent()->associate($request->parent_id);
        } else {
            $project->parent()->disassociate();
        }
        $project->save();

        return new ProjectResource($project);
    }

    public function destroy(Project $project): Response
    {
        $project->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function tree(Request $request): Collection
    {
        $request->validate([
            'exclude' => [
                'nullable',
                'int',
            ],
        ]);

        return $this->addCanUpdate(Project::queryByParent(null, $request->input('exclude')));
    }

    private function addCanUpdate(Collection $items): Collection
    {
        return $items->map(function ($e) {
            $e['can_update'] = request()->user()->can('update', $e);
            $e['children'] = $this->addCanUpdate($e['children']);

            return $e;
        });
    }
}
