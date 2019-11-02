<?php

namespace Modules\Badges\Util;

use Carbon\Carbon;

use Mpdf\Mpdf;
use \Mpdf\Output;

class BadgeCreator {

    private $persons;
    private $logo;

    public $pageFormat = 'A4';

    public $badgeWidth = 74;
    public $badgeHeight = 105;

    public $padding = 5;

    public function __construct($persons) {
        $this->persons = $persons;
        $this->logo = public_path('img/logo_card.png');
    }

    public function setLogo($path) {
        $this->logo = $path;
    }

    public function createPdf($title) {
        $persons = collect($this->persons)
            ->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE)
            ->values()
            ->all();

        $punch_hole_size = 6;
        $punch_hole_distance_center = 9;

        $mpdf = new Mpdf([
            'format' => $this->pageFormat,
            'orientation' => 'L',
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
            margin-top: 3mm;
        }
        .portrait {
            text-align:center;
            padding-top:2mm;
            padding-bottom:2mm;
        }
        .name {
            text-align: center;
            font-size: 270%;
            padding: 0;
            margin: 0;
        }
        .position {
            text-align: center;
            font-weight: normal;
            padding: 0;
            margin: 0;
        }
        .issued {
            text-align: right;
            font-size: 6pt;
        }
        .code_no {
            font-size: 8pt;
        }
        ', 1);

        $pageWidth = $mpdf->w;
        $pageHeight = $mpdf->h;

        // Validate badge dimensions
        if ($this->badgeWidth > $pageWidth) {
            throw new \Exception('Badge width (' . $this->badgeWidth . ') greater than page width (' . $pageWidth . ')');
        }
        if ($this->badgeHeight > $pageHeight) {
            throw new \Exception('Badge height (' . $this->badgeHeight . ') greater than page height (' . $pageHeight . ')');
        }

        // Calculate badges per dimension and page
        $badgeFrontAndBackWidth = $this->badgeWidth * 2;
        $badgesX = floor($pageWidth / $badgeFrontAndBackWidth);
        $badgesY = floor($pageHeight / $this->badgeHeight);
        $badgesPerPage = $badgesX * $badgesY;

        // Iterate over all persons
        for ($i = 0; $i < count($persons); $i++) {

            // Badge container starting position
            $x = $badgeFrontAndBackWidth * ($i % $badgesX);
            $y = $this->badgeHeight * floor(($i % $badgesPerPage) / $badgesX);

            // Decide if new page should be added
            if ($i % $badgesPerPage == 0) {
                $mpdf->AddPage('L');
            }

            // Calculate starting positions and dimensions including padding
            $xp = $x + $this->padding;
            $yp = $y + $this->padding;
            $wp = $this->badgeWidth - (2 * $this->padding);
            $hp = $this->badgeHeight - (2 * $this->padding);
            $xpb = $x + $this->badgeWidth + $this->padding;
            $ypb = $pageHeight - $yp - $hp;

            // Page number
            $page = floor($i / $badgesPerPage) + 1;

            // Line on the right
            $mpdf->Line($x + $badgeFrontAndBackWidth, $y, $x + $badgeFrontAndBackWidth, $y + $this->badgeHeight);
            
            // Line on the bottom
            $mpdf->Line($x, $y + $this->badgeHeight, $x + $badgeFrontAndBackWidth, $y + $this->badgeHeight);

            // Divider between front and back side
            $mpdf->SetLineWidth(0.05);
            $mpdf->SetDrawColor(192);
            $mpdf->Line($x + $this->badgeWidth, $y, $x + $this->badgeWidth, $y + $this->badgeHeight);
            $mpdf->SetLineWidth(0.2);
            $mpdf->SetDrawColor(0);

            // DEBUG: Padding rectangle
            // $mpdf->Rect($xp, $yp, $wp, $hp);
            // $mpdf->Rect($xpb, $yp, $wp, $hp);

            // // DEBUG: Info
            // $mpdf->WriteFixedPosHTML("$i: ($x,$y)<br>".$persons[$i]['name']."<br>".$persons[$i]['position']."<br>Page: ".$page, $xp, $yp, $wp, $hp);

            // // Line around the block (front and back)
            // $mpdf->Rect($x, $y, $bw, $bh);

            // // Line between front and back side
            // $mpdf->SetLineWidth(0.05);
            // $mpdf->SetDrawColor(192);
            // $mpdf->Line($x + ($bw / 2), $y, $x + ($bw / 2), $y + $bh);
            // $mpdf->SetLineWidth(0.2);
            // $mpdf->SetDrawColor(0);

            // Portrait Picture
            // if (isset($persons[$i]['picture'])) {
            //     self::fitImage($mpdf, $persons[$i]['picture'], $x + $padding, $y + $padding, ($bw / 2) - (2 * $padding), $bh - (2 * $padding));
            // }

            if (isset($persons[$i]['picture'])) {
                $portrait = '<div class="portrait"><img src="'. $persons[$i]['picture'] .'" style="height: 50mm;"></div>';
                // $mpdf->WriteFixedPosHTML('<img src="'. \Storage::path($persons[$i]['picture']) .'" style="width: 30mm">', $x + $padding, $y + $padding, 100, 100, 'hidden');
            } else {
                $portrait = '';
            }

            $content = '
                <div class="logo">
                    <img src="'. $this->logo .'" style="height: 15mm;">
                </div>
                '.$portrait.'
                <h1 class="name">' . $persons[$i]['name'] . '</h1>
                <h2 class="position">' . $persons[$i]['position'] . '</h2>';

            // Name
            $mpdf->WriteFixedPosHTML($content, $xp, $yp, $wp, $hp, 'auto');

            // $mpdf->writeHTML('<div class="issued" style="background: cyan; position: absolute; bottom: '.($yp).'mm; right: '.($pageWidth-$xp).'">Issued: ' . Carbon::today()->toDateString() . '</div>');
            // $mpdf->WriteFixedPosHTML('<div class="issued" style="position: absolute; height: '.$hp.'mm; bottom: 0; right: 0">Issued: ' . Carbon::today()->toDateString() . '</div>', $xp, $yp, $wp, $hp, 'auto');

            // Issued
            // $mpdf->writeHTML('<div class="issued" style="position: absolute; width: '.$wp.'mm; left:'.$xp.'mm; bottom: '.$ypb.'mm;">Issued: ' . Carbon::today()->toDateString() . '</div>');
            $mpdf->writeHTML('<div class="issued" style="position: absolute; width: '.$wp.'mm; left:'.$xp.'mm; top: '.($yp + $hp).'mm;">Issued: ' . Carbon::today()->toDateString() . '</div>');

            // Punch hole
            $mpdf->writeHTML('<div style="position: absolute; left: '. ($x + ($this->badgeWidth / 2) - ($punch_hole_size / 2)) .'mm; top: '. ($y + $punch_hole_distance_center - ($punch_hole_size / 2)) .'mm; width: '. $punch_hole_size .'mm; height: ' . $punch_hole_size . 'mm; border-radius: ' . ($punch_hole_size / 2) .'mm; border: 1px dotted black;"></div>');

            // $content = '
            // <div class="logo">
            //     <img src="'. $this->logo .'" style="height: 15mm;">
            // </div>
            // <h1 class="name">' . $persons[$i]['name'] . '</h1>
            // <h2 class="position">' . $persons[$i]['position'] . '</h2>';

            // if (isset($persons[$i]['picture'])) {
            //     $mpdf->WriteFixedPosHTML('<img src="'. \Storage::path($persons[$i]['picture']) .'" style="width: 30mm">', $x + $padding, $y + $padding, 100, 100, 'hidden');
            // }

            // // Borders
            // $mpdf->writeHTML('<div style="position: absolute; left: '.$x.'mm; top: '.$y.'mm; width: '.$bw.'mm; height: '.$bh.'mm; border: 1px dotted black;"></div>');
            // $mpdf->writeHTML('<div style="position: absolute; left: '.$x.'mm; top: '.($y+ ($bh/2)).'mm; width: '.$bw.'mm; border-top: 1px dotted black;"></div>');

            // // Front-side content
            // $mpdf->writeHTML('<div style="position: absolute; left: '. ($x + $padding) . 'mm; top: '. ($y + $padding).'mm; width: '. ($bw - (2 * $padding)) .'mm; height: '. ($bsh - (2 * $padding)) .'mm;">
            // ' . $content . '
            // </div>');

            // // Back-side content
            // $mpdf->writeHTML('<div style="position: absolute; left: '. ($x + $padding) . 'mm; top: '. ($y + $bsh + $padding).'mm; width: '. ($bw - (2 * $padding)) .'mm; height: '. ($bsh - (2 * $padding)) .'mm; rotate: 180;">
            // ' . $content . '
            // </div>');

            // Issued
            // $mpdf->WriteFixedPosHTML('<div class="issued">Issued: ' . Carbon::today()->toDateString() . '</div>', $x + ($bw / 2) + $padding, $y + $padding, ($bw / 2) - (2 * $padding), $bh - (2 * $padding), 'auto');

            // // Punch hole
            // $mpdf->writeHTML('<div style="position: absolute; left: '. ($x + ($bw / 2) - ($punch_hole_size / 2)) .'mm; top: '. ($y + $punch_hole_distance_center - ($punch_hole_size / 2)) .'mm; width: '. $punch_hole_size .'mm; height: ' . $punch_hole_size . 'mm; border-radius: ' . ($punch_hole_size / 2) .'mm; border: 1px dotted black;"></div>');
            
            // // QR Code of ID
            // if (!empty($persons[$i]['code'])) {
            //     $mpdf->WriteFixedPosHTML('<barcode code="'. $persons[$i]['code'] .'" type="QR" class="barcode" size="0.5" error="M" disableborder="1" /><br><small class="code_no">' . $persons[$i]['code'].'</small>', $x + $padding - 3, $y + $padding + 43, 30, 30, 'auto');
            // }

        }

        $mpdf->Output($title . ' ' .Carbon::now()->toDateString() . '.pdf', Output\Destination::DOWNLOAD);
    }

    private static function fitImage($mpdf, $imageSource, $x, $y, $containerWidth, $containerHeight)
    {
        // Get image width
        $imageWidth				= getimagesize($imageSource)[0];
        // Get image height
        $imageHeight			= getimagesize($imageSource)[1];
        // Get image aspect ratio
        $imageRatio				= $imageWidth / $imageHeight;
        // Get container aspect ratio
        $containerRatio			= $containerWidth / $containerHeight;
        
        // Decide if image should increase in height or width
        if ($imageRatio > $containerRatio) {
            $width = $containerWidth;
            $height = $containerWidth / $imageRatio;
        } else if ($imageRatio < $containerRatio) {
            $width = $containerHeight * $imageRatio;
            $height = $containerHeight;
        } else {
            $width = $containerWidth;
            $height = $containerHeight;
        }

        // Center image
        $offsetX = ($containerWidth / 2) - ($width / 2);
        $offsetY = ($containerHeight / 2) - ($height / 2);

        // Write image to PDF
        $mpdf->Image($imageSource, $x + $offsetX, $y + $offsetY, $width, $height);
    }

}