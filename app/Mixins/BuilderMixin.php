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
                    $this->query->selectRaw("YEAR($column) as date_label");
                    break;
                case 'months':
                    $this->query->selectRaw("DATE_FORMAT($column, '%Y-%m') as date_label");
                        break;
                case 'weeks':
                    $this->query->selectRaw("DATE_FORMAT($column, '%x-W%v') as date_label");
                    break;
                default: // days
                    $this->query->selectRaw("DATE($column) as date_label");
            }
            return $this->query
                ->groupBy('date_label')
                ->orderBy($column, $orderDirection);
        };
    }
}
