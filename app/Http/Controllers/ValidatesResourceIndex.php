<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;

trait ValidatesResourceIndex
{
    protected function validateFilter(): void
    {
        request()->validate([
            'filter' => [
                'nullable',
                'string',
            ],
        ]);
    }

    protected function validatePagination(): void
    {
        request()->validate([
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
        ]);
    }

    protected function validateSorting(array $sortFields): void
    {
        request()->validate([
            'sortBy' => [
                'nullable',
                'alpha_dash',
                'filled',
                Rule::in($sortFields),
            ],
            'sortDirection' => [
                'nullable',
                Rule::in(['asc', 'desc']),
            ],
        ]);
    }

    protected function getFilter(): ?string
    {
        if (request()->filled('filter')) {
            return trim(request()->input('filter'));
        }

        return null;
    }

    protected function getSortBy(string $defaultField): string
    {
        return request()->input('sortBy', $defaultField);
    }

    protected function getSortDirection(?string $defaultDirection = 'asc'): string
    {
        return request()->input('sortDirection', $defaultDirection);
    }

    protected function getPageSize(?int $defaultSize = 10): int
    {
        return request()->input('pageSize', $defaultSize);
    }

    protected function getIncludes(): array
    {
        if (request()->filled('include')) {
            return preg_split('/,/', request()->input('include'));
        }

        return [];
    }
}
