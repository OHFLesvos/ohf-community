<?php

namespace App\Mixins;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class BuilderMixin
{
    /**
     * @return \Closure
     */
    public function groupByDateGranularity()
    {
        return function (?string $granularity = 'days', ?string $column = 'created_at', ?string $orderDirection = 'asc', ?string $group_by_column_name = 'date_label') {
            switch ($granularity) {
                case 'years':
                    $this->getQuery()->selectRaw("YEAR(`${column}`) as `${group_by_column_name}`");
                    break;
                case 'months':
                    $this->getQuery()->selectRaw("DATE_FORMAT(`${column}`, '%Y-%m') as `${group_by_column_name}`");
                    break;
                case 'weeks':
                    $this->getQuery()->selectRaw("DATE_FORMAT(`${column}`, '%x-W%v') as `${group_by_column_name}`");
                    break;
                default: // days
                    $this->getQuery()->selectRaw("DATE(`${column}`) as `${group_by_column_name}`");
            }

            return $this->getQuery()
                ->groupBy($group_by_column_name)
                ->orderBy($column, $orderDirection);
        };
    }
}
