<?php

namespace App\Exports\Accounting;

use App\Exports\BaseExport;
use App\Models\Accounting\MoneyTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Setting;

abstract class BaseMoneyTransactionsExport extends BaseExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting
{
    public function headings(): array
    {
        $headings = [
            __('app.date'),
            __('accounting.income'),
            __('accounting.spending'),
            __('accounting.receipt_no'),
            __('accounting.attendee'),
            __('app.category'),
        ];
        if (self::useSecondaryCategories()) {
            $headings[] =  __('app.secondary_category');
        }
        $headings[] = __('app.project');
        if (self::useLocations()) {
            $headings[] = __('app.location');
        }
        if (self::useCostCenters()) {
            $headings[] = __('accounting.cost_center');
        }
        return array_merge($headings, [
            __('app.description'),
            __('app.registered'),
            __('accounting.controlled_at'),
            __('accounting.controlled_by'),
            __('accounting.booked'),
            __('app.author'),
            __('app.remarks'),
        ]);
    }

    /**
     * @param MoneyTransaction $transaction
     */
    public function map($transaction): array
    {
        $audit = $transaction->audits()->first();
        $data = [
            $transaction->date,
            $transaction->type == 'income' ? $transaction->amount : '',
            $transaction->type == 'spending' ? $transaction->amount : '',
            $transaction->receipt_no,
            $transaction->attendee,
            $transaction->category,
        ];
        if (self::useSecondaryCategories()) {
            $data[] = $transaction->secondary_category;
        }
        $data[] = $transaction->project;
        if (self::useLocations()) {
            $data[] = $transaction->location;
        }
        if (self::useCostCenters()) {
            $data[] = $transaction->cost_center;
        }
        return array_merge($data, [
            $transaction->description,
            $transaction->created_at,
            $transaction->controlled_at,
            $transaction->controlled_by !== null ? $transaction->controller->name : null,
            $transaction->booked ? __('app.yes') : __('app.no'),
            isset($audit) ? $audit->getMetadata()['user_name'] : '',
            $transaction->remarks,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    protected function applyStyles(Worksheet $sheet)
    {
        parent::applyStyles($sheet);
        $sheet->getStyle('B2:B' . $sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));
        $sheet->getStyle('C2:C' . $sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKRED));
    }

    private static function useSecondaryCategories(): bool
    {
        return Setting::get('accounting.transactions.use_secondary_categories') ?? false;
    }

    private static function useLocations(): bool
    {
        return Setting::get('accounting.transactions.use_locations') ?? false;
    }

    private static function useCostCenters(): bool
    {
        return Setting::get('accounting.transactions.use_cost_centers') ?? false;
    }
}
