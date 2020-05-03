<?php

namespace App\Mixins;

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
                    $this->query->selectRaw("YEAR(`$column`) as `$group_by_column_name`");
                    break;
                case 'months':
                    $this->query->selectRaw("DATE_FORMAT(`$column`, '%Y-%m') as `$group_by_column_name`");
                        break;
                case 'weeks':
                    $this->query->selectRaw("DATE_FORMAT(`$column`, '%x-W%v') as `$group_by_column_name`");
                    break;
                default: // days
                    $this->query->selectRaw("DATE(`$column`) as `$group_by_column_name`");
            }
            return $this->query
                ->groupBy($group_by_column_name)
                ->orderBy($column, $orderDirection);
        };
    }
}
