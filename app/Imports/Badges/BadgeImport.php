<?php

namespace App\Imports\Badges;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BadgeImport implements WithHeadingRow
{
    use Importable;
}
