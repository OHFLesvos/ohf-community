<?php

namespace App\Support;

use Illuminate\Support\Collection;

class ChartResponseBuilder
{
    private $datasets = [];
    private $labels;

    public function __construct()
    {
        $this->labels = collect();
    }

    public function dataset(string $title, Collection $data, ?string $unit = null, $sort = true)
    {
        $labels = $data->keys()
            ->map(fn ($v) => strval($v));

        $unique_labels = $this->labels
            ->concat($labels)
            ->unique();
        if ($sort) {
            $unique_labels = $unique_labels->sort(SORT_NATURAL);
        }
        $this->labels = $unique_labels->values();

        $this->datasets[] = [
            'label' => $title,
            'data' => $data,
            'unit' => $unit !== null ? $unit : __('app.quantity'),
        ];

        return $this;
    }

    public function build()
    {
        $num_datasets = count($this->datasets);
        for ($i = 0; $i < $num_datasets; $i++) {
            $this->datasets[$i]['data'] = $this->padData($this->datasets[$i]['data']);
        }
        return response()->json([
            'labels' => $this->labels,
            'datasets' => $this->datasets,
        ]);
    }

    private function padData($data)
    {
        $newData = [];
        foreach ($this->labels as $label) {
            $newData[] = $data[$label] ?? null;
        }
        return $newData;
    }
}
