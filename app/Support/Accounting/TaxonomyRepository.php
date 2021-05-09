<?php

namespace App\Support\Accounting;

use App\Models\Accounting\Category;
use App\Models\Accounting\Project;

class TaxonomyRepository
{
    public function getNestedCategories(?int $parent = null, int $level = 0, bool $enabledOnly = false): array
    {
        $categories = [];
        foreach(Category::query()
            ->select('id', 'name')
            ->when($enabledOnly, fn($q) => $q->where('enabled', true))
            ->orderBy('name', 'asc')
            ->when($parent !== null, fn ($q) => $q->forParent($parent), fn ($q) => $q->isRoot())
            ->get() as $category) {
            $categories[$category['id']] = str_repeat("&nbsp;", 4 * $level) . $category['name'];
            foreach ($this->getNestedCategories($category['id'], $level + 1, $enabledOnly) as $k => $v) {
                $categories[$k] = $v;
            }
        }
        return $categories;
    }

    public function getNestedProjects(?int $parent = null, int $level = 0, bool $enabledOnly = false): array
    {
        $projects = [];
        foreach(Project::query()
            ->select('id', 'name')
            ->when($enabledOnly, fn($q) => $q->where('enabled', true))
            ->orderBy('name', 'asc')
            ->when($parent !== null, fn ($q) => $q->forParent($parent), fn ($q) => $q->isRoot())
            ->get() as $project) {
            $projects[$project['id']] = str_repeat("&nbsp;", 4 * $level) . $project['name'];
            foreach ($this->getNestedProjects($project['id'], $level + 1, $enabledOnly) as $k => $v) {
                $projects[$k] = $v;
            }
        }
        return $projects;
    }
}
