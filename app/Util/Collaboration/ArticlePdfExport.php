<?php

namespace App\Util\Collaboration;

use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class ArticlePdfExport
{
    public static function createPDF($title, $content)
    {
        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 20,
            'margin_bottom' => 20,
        ]);
        $mpdf->showImageErrors = true;

        // Header
        $mpdf->SetHTMLHeader('
        <div style="text-align: right; font-weight: bold;">
            ' . config('app.name') . ' - ' . __('Knowledge Base') . '
        </div>');

        // Footer
        $mpdf->SetHTMLFooter('
        <table width="100%">
            <tr>
                <td width="33%">{DATE j-m-Y}</td>
                <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right;">' . $title . '</td>
            </tr>
        </table>');

        // Style
        $style = '
            body {
                font-family: Helvetica;
            }
        ';
        $mpdf->WriteHTML($style, HTMLParserMode::HEADER_CSS);

        // Content

        // Make image paths relative
        $content = str_replace('src="/', 'src="./', $content);

        $mpdf->WriteHTML($content);

        $mpdf->Output($title . '.pdf', Destination::DOWNLOAD);
    }
}
