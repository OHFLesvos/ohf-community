<?php

namespace App\Mixins;

class BuilderMixin
{
    /**
     * @return \Closure
     */
    public function groupByDateGranularity()
    {
        return function ($granularity = 'days', $column = 'created_at', $orderDirection = 'asc') {
            switch ($granularity) {
                case 'years':
                    $this->query->selectRaw("YEAR($column) as year")
                        ->groupBy('year')
                        ->orderBy($column, $orderDirection);
                    break;
                case 'months':
                    $this->query->selectRaw("DATE_FORMAT($column, '%Y-%m') as month")
                        ->groupBy('month')
                        ->orderBy($column, $orderDirection);
                        break;
                case 'weeks':
                    $this->query->selectRaw("DATE_FORMAT($column, '%x-%v') as week")
                        ->groupBy('week')
                        ->orderBy($column, $orderDirection);
                    break;
                default: // days
                    $this->query->selectRaw("DATE($column) as date")
                        ->groupBy('date')
                        ->orderBy($column, $orderDirection);
            }
            return $this->query;
        };
    }
}
