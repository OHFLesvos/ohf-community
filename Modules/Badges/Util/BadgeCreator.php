<?php

namespace Modules\Badges\Util;

use Carbon\Carbon;

use Mpdf\Mpdf;
use \Mpdf\Output;

class BadgeCreator {

    private $persons;
    private $logo;

    public $pageFormat = 'A4';
    public $orientation = 'landscape';

    public $badgeWidth = 74;
    public $badgeHeight = 105;

    public $addIssuedDate = true;

    public $padding = 5;

    public $punchHoleSize = 6;
    public $punchHoleDistanceCenter = 9;

    public $mirror = true;

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

        $mpdf = new Mpdf([
            'format' => $this->pageFormat,
            'orientation' => $this->orientation,
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
                $mpdf->AddPage();
            }

            // Calculate starting positions and dimensions including padding
            $xp = $x + $this->padding;
            $yp = $y + $this->padding;
            $wp = $this->badgeWidth - (2 * $this->padding);
            $hp = $this->badgeHeight - (2 * $this->padding);
            $xpb = $x + $this->badgeWidth + $this->padding;
            $ypb = $pageHeight - $yp - $hp;
            $rx0 = $pageWidth - ($this->badgeWidth * 2 * $badgesX);
            $rx = $rx0 + ($badgeFrontAndBackWidth * ($badgesX - 1- ($i % $badgesX)));

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

            $content = '';

            // Logo
            $content.= '<img src="'. $this->logo .'" style="height: 15mm; margin-top: 3mm; text-align: center;">';

            // Picture
            if (isset($persons[$i]['picture'])) {
                $content.= '<div style="text-align:center; padding-top:2mm; padding-bottom:2mm;"><img src="'. $persons[$i]['picture'] .'" style="height: 50mm;"></div>';
            }

            // Name
            $content.= '<h1 style="font-size: 270%; text-align: center; padding: 0; margin: 0; text-align: center;">' . $persons[$i]['name'] . '</h1>';

            // Position
            $content.= '<h2 style="text-align: center; font-weight: normal; padding: 0; margin: 0;">' . $persons[$i]['position'] . '</h2>';

            // Write content
            $mpdf->WriteFixedPosHTML($content, $xp, $yp, $wp, $hp, 'auto');
            if ($this->mirror) {
                $mpdf->WriteFixedPosHTML($content, $xp + $this->badgeWidth, $yp, $wp, $hp, 'auto');
            }

            // Issued
            if ($this->addIssuedDate) {
                $mpdf->writeHTML('<div class="issued" style="position: absolute; width: '.$wp.'mm; left:'.$xp.'mm; top: '.($yp + $hp).'mm;">Issued: ' . Carbon::today()->toDateString() . '</div>');
            }

            // Punch hole
            $mpdf->writeHTML('<div style="position: absolute; left: '. ($x + ($this->badgeWidth / 2) - ($this->punchHoleSize / 2)) .'mm; top: '. ($y + $this->punchHoleDistanceCenter - ($this->punchHoleSize / 2)) .'mm; width: '. $this->punchHoleSize .'mm; height: ' . $this->punchHoleSize . 'mm; border-radius: ' . ($this->punchHoleSize / 2) .'mm; border: 1px dotted black;"></div>');

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