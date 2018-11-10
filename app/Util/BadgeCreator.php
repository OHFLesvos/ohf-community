<?php

namespace App\Util;

use Carbon\Carbon;
use Mpdf\Mpdf;
use \Mpdf\Output;

class BadgeCreator {

    private $persons;
    private $logo;

    public function __construct($persons) {
        $this->persons = $persons;
        $this->logo = public_path('img/logo_card.png');
    }

    public function setLogo($path) {
        $this->logo = $path;
    }

    public function createPdf($title) {
        $persons = collect($this->persons)
            ->sortBy('name')
            ->values()
            ->all();

        $padding = 10;

        $punch_hole_size = 6;
        $punch_hole_distance_center = 12;

        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
        ]);
        $mpdf->SetDisplayMode('fullpage');

        // Style
        $mpdf->writeHTML('body { 
            font-family: Helvetica; 
        }

        .logo {
            text-align: center;
            margin-top: 6mm;
        }

        .name {
            text-align: center;
            font-size: 30pt;
            padding: 0;
            margin: 0;
            margin-top: 1mm;
        }

        .position {
            text-align: center;
            font-weight: normal;
            padding: 0;
            margin: 0;
        }

        .issued {
            text-align: right;
            font-size: 8pt;
        }
        ', 1);

        $h = $mpdf->h;
        $w = $mpdf->w;

        $bw = $w / 2;
        $bh = $h / 2;
        $bsh = $bh / 2;

        for ($i = 0; $i < count($persons); $i++) {
            
            $x = $i % 2 == 0 ? 0 : $w / 2;
            $y = $i % 4 == 0 || $i % 4 == 1 ? 0 : $h / 2;

            if ($i % 4 == 0) {
                $mpdf->AddPage();
            }

            $content = '
            <div class="logo">
                <img src="'. $this->logo .'" style="height: 15mm;">
            </div>
            <h1 class="name">' . $persons[$i]['name'] . '</h1>
            <h2 class="position">' . $persons[$i]['position'] . '</h2>';

            // TODO image <img src="{{ Storage::path($helper->person->portrait_picture) }}">

            // Borders
            $mpdf->writeHTML('<div style="position: absolute; left: '.$x.'mm; top: '.$y.'mm; width: '.$bw.'mm; height: '.$bh.'mm; border: 1px dotted black;"></div>');
            $mpdf->writeHTML('<div style="position: absolute; left: '.$x.'mm; top: '.($y+ ($bh/2)).'mm; width: '.$bw.'mm; border-top: 1px dotted black;"></div>');

            // Front-side content
            $mpdf->writeHTML('<div style="position: absolute; left: '. ($x + $padding) . 'mm; top: '. ($y + $padding).'mm; width: '. ($bw - (2 * $padding)) .'mm; height: '. ($bsh - (2 * $padding)) .'mm;">
            ' . $content . '
            </div>');

            // Back-side content
            $mpdf->writeHTML('<div style="position: absolute; left: '. ($x + $padding) . 'mm; top: '. ($y + $bsh + $padding).'mm; width: '. ($bw - (2 * $padding)) .'mm; height: '. ($bsh - (2 * $padding)) .'mm; rotate: 180;">
            ' . $content . '
            </div>');

            // Issued
            $mpdf->WriteFixedPosHTML('<div class="issued">Issued: ' . Carbon::today()->toDateString() . '</div>', $x + $padding, $y + $bsh - $padding - 3, $bw - (2 * $padding), 10, 'auto');

            // Punch hole
            $mpdf->writeHTML('<div style="position: absolute; left: '. ($x + ($bw / 2) - ($punch_hole_size / 2)) .'mm; top: '. ($y + $punch_hole_distance_center - ($punch_hole_size / 2)) .'mm; width: '. $punch_hole_size .'mm; height: ' . $punch_hole_size . 'mm; border-radius: ' . ($punch_hole_size / 2) .'mm; border: 1px dotted black;"></div>');
            
            // QR Code of ID
            if (!empty($persons[$i]['id'])) {
                $mpdf->WriteFixedPosHTML('<barcode code="'. $persons[$i]['id'] .'" type="QR" class="barcode" size="0.5" error="M" disableborder="1" />', $x + $padding, $y + $padding + 42, 30, 30, 'auto');
            }

        }

        $mpdf->Output($title . ' ' .Carbon::now()->toDateString() . '.pdf', Output\Destination::DOWNLOAD);
    }

}