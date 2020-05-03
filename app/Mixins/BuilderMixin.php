<?php

namespace App\Mixins;

class BuilderMixin
{
    /**
     * @return \Closure
     */
    public function groupByDateGranularity()
    {
        return function (?string $granularity = 'days', ?string $column = 'created_at', ?string $orderDirection = 'asc') {
            switch ($granularity) {
                case 'years':
                    $this->query->selectRaw("YEAR($column) as date");
                    break;
                case 'months':
                    $this->query->selectRaw("DATE_FORMAT($column, '%Y-%m') as date");
                        break;
                case 'weeks':
                    $this->query->selectRaw("DATE_FORMAT($column, '%x-W%v') as date");
                    break;
                default: // days
                    $this->query->selectRaw("DATE($column) as date");
            }
            return $this->query
                ->groupBy('date')
                ->orderBy($column, $orderDirection);
        };
    }
}
