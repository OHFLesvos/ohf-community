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
            __('accounting.receipt_no'),
            __('accounting.income'),
            __('accounting.spending'),
            __('accounting.fees'),
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
            __('accounting.supplier'),
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
            $transaction->receipt_no,
            $transaction->type == 'income' ? $transaction->amount : '',
            $transaction->type == 'spending' ? $transaction->amount : '',
            $transaction->fees,
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
            optional($transaction->supplier)->name,
            $transaction->created_at,
            $transaction->controlled_at,
            $transaction->controlled_by !== null ? $transaction->controller->name : null,
            $transaction->booked ? __('app.yes') : __('app.no'),
            isset($audit) && isset($audit->getMetadata()['user_name']) ? $audit->getMetadata()['user_name'] : '',
            $transaction->remarks,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    protected function applyStyles(Worksheet $sheet)
    {
        parent::applyStyles($sheet);
        $sheet->getStyle('C2:C' . $sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));
        $sheet->getStyle('D2:D' . $sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKRED));
        $sheet->getStyle('E2:E' . $sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKRED));
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
