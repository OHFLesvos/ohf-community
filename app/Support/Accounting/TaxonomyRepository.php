<?php

namespace App\Support\Accounting;

use App\Models\Accounting\Category;
use App\Models\Accounting\Project;

class TaxonomyRepository
{
    public function getNestedCategories(?int $parent = null, $level = 0): array
    {
        $categories = [];
        foreach(Category::query()
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->when($parent !== null, fn ($q) => $q->forParent($parent), fn ($q) => $q->isRoot())
            ->get() as $category) {
            $categories[$category['id']] = str_repeat("&nbsp;", 4 * $level) . $category['name'];
            foreach ($this->getNestedCategories($category['id'], $level + 1) as $k => $v) {
                $categories[$k] = $v;
            }
        }
        return $categories;
    }

    public function getNestedProjects(?int $parent = null, $level = 0): array
    {
        $categories = [];
        foreach(Project::query()
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->when($parent !== null, fn ($q) => $q->forParent($parent), fn ($q) => $q->isRoot())
            ->get() as $category) {
            $categories[$category['id']] = str_repeat("&nbsp;", 4 * $level) . $category['name'];
            foreach ($this->getNestedProjects($category['id'], $level + 1) as $k => $v) {
                $categories[$k] = $v;
            }
        }
        return $categories;
    }
}
