<?php

namespace App\Imports\CommunityVolunteers;

use Maatwebsite\Excel\HeadingRowImport as ExcelHeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class HeadingRowImport extends ExcelHeadingRowImport
{
    public function __construct(int $headingRow = 1)
    {
        parent::__construct($headingRow);
        HeadingRowFormatter::default('none');
    }
}
