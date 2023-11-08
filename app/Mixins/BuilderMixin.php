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
        return function (?string $granularity = 'days', ?string $column = 'created_at', ?string $orderDirection = 'asc', ?string $groupByColumnName = 'date_label', ?string $aggregateColumnName = 'aggregated_value') {
            switch ($granularity) {
                case 'years':
                    $this->getQuery()->selectRaw("YEAR(`{$column}`) as `{$groupByColumnName}`");
                    break;
                case 'months':
                    $this->getQuery()->selectRaw("DATE_FORMAT(`{$column}`, '%Y-%m') as `{$groupByColumnName}`");
                    break;
                case 'weeks':
                    $this->getQuery()->selectRaw("DATE_FORMAT(`{$column}`, '%x-W%v') as `{$groupByColumnName}`");
                    break;
                default: // days
                    $this->getQuery()->selectRaw("DATE(`{$column}`) as `{$groupByColumnName}`");
            }

            return $this->getQuery()
                ->selectRaw('COUNT(*) AS `'.$aggregateColumnName.'`')
                ->groupBy($groupByColumnName)
                ->orderBy($column, $orderDirection);
        };
    }
}
