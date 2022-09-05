<?php

namespace App\Util\Badges;

use Carbon\Carbon;
use Exception;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class BadgeCreator
{
    /**
     * Relative path to the logo image file
     */
    public ?string $logo = null;

    /**
     * The page format (e.g. A4)
     */
    public string $pageFormat = 'A4';

    /**
     * The paper orientation [landscape, portrait]
     */
    public string $orientation = 'landscape';

    /**
     * Badge with (mm)
     */
    public int $badgeWidth = 74;

    /**
     * Badge height (mm)
     */
    public int $badgeHeight = 105;

    /**
     * Whether the issued date should be added
     */
    public bool $addIssuedDate = true;

    /**
     * Padding
     */
    public int $padding = 5;

    /**
     * Punch hole size
     */
    public int $punchHoleSize = 6;

    /**
     * Punch hole distance to center
     */
    public int $punchHoleDistanceCenter = 9;

    /**
     * Whether to mirror front onto the back side
     */
    public bool $mirror = true;

    /**
     * Data of persons to be printed on the badges
     *
     * @var array|Collection
     */
    private $persons;

    public function __construct($persons)
    {
        $this->persons = $persons;
    }

    public function createPdf($title)
    {
        $persons = collect($this->persons)
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
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
        ', 1);

        $pageWidth = $mpdf->w;
        $pageHeight = $mpdf->h;

        // Validate badge dimensions
        if ($this->badgeWidth > $pageWidth) {
            throw new Exception('Badge width ('.$this->badgeWidth.') greater than page width ('.$pageWidth.')');
        }
        if ($this->badgeHeight > $pageHeight) {
            throw new Exception('Badge height ('.$this->badgeHeight.') greater than page height ('.$pageHeight.')');
        }

        // Calculate badges per dimension and page
        $badgeFrontAndBackWidth = $this->badgeWidth * 2;
        $badgesX = floor($pageWidth / $badgeFrontAndBackWidth);
        $badgesY = floor($pageHeight / $this->badgeHeight);
        $badgesPerPage = $badgesX * $badgesY;

        // Iterate over all persons
        $num_persons = count($persons);
        for ($i = 0; $i < $num_persons; $i++) {
            // Badge container starting position
            $x = $badgeFrontAndBackWidth * ($i % $badgesX);
            $y = $this->badgeHeight * floor(($i % $badgesPerPage) / $badgesX);

            // Decide if new page should be added
            if ($i % $badgesPerPage === 0) {
                $mpdf->AddPage();
            }

            // Calculate starting positions and dimensions including padding
            $xp = $x + $this->padding;
            $yp = $y + $this->padding;
            $wp = $this->badgeWidth - (2 * $this->padding);
            $hp = $this->badgeHeight - (2 * $this->padding);

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
            if ($this->logo !== null) {
                $content .= '<div style="text-align:center"><img src="'.$this->logo.'" style="height: 15mm; margin-top: 3mm;"></div>';
            }

            // Picture
            if (isset($persons[$i]['picture'])) {
                $content .= '<div style="text-align:center; padding-top:2mm; padding-bottom:2mm;"><img src="'.$persons[$i]['picture'].'" style="height: 50mm;"></div>';
                $nameTopMargin = 0;
            } else {
                $nameTopMargin = 22;
            }

            // Name
            $content .= '<h1 style="font-size: 270%; text-align: center; padding: 0; margin: 0; text-align: center; margin-top: '.$nameTopMargin.'mm">'.$persons[$i]['name'].'</h1>';

            // Position
            $content .= '<h2 style="text-align: center; font-weight: normal; padding: 0; margin: 0;">'.$persons[$i]['position'].'</h2>';

            // Write content
            $mpdf->WriteFixedPosHTML($content, $xp, $yp, $wp, $hp, 'auto');
            if ($this->mirror) {
                $mpdf->WriteFixedPosHTML($content, $xp + $this->badgeWidth, $yp, $wp, $hp, 'auto');
            }

            // Issued
            if ($this->addIssuedDate) {
                $mpdf->writeHTML('<div class="issued" style="position: absolute; width: '.$wp.'mm; left:'.$xp.'mm; top: '.($yp + $hp).'mm;">Issued: '.Carbon::today()->toDateString().'</div>');
            }

            // Punch hole
            $mpdf->writeHTML('<div style="position: absolute; left: '.($x + ($this->badgeWidth / 2) - ($this->punchHoleSize / 2)).'mm; top: '.($y + $this->punchHoleDistanceCenter - ($this->punchHoleSize / 2)).'mm; width: '.$this->punchHoleSize.'mm; height: '.$this->punchHoleSize.'mm; border-radius: '.($this->punchHoleSize / 2).'mm; border: 1px dotted black;"></div>');
        }

        $mpdf->Output($title.' '.Carbon::now()->toDateString().'.pdf', Destination::DOWNLOAD);
    }
}
