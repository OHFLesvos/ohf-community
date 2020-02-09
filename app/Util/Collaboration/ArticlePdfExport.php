<?php

namespace App\Util\Collaboration;

use Illuminate\Support\Facades\Config;

class ArticlePdfExport
{
    public static function createPDF($title, $content)
    {
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 20,
            'margin_bottom' => 20,
            // 'margin_header' => 0,
            // 'margin_footer' => 0,
        ]);
        $mpdf->showImageErrors = true;

        // Header
        $mpdf->SetHTMLHeader('
        <div style="text-align: right; font-weight: bold;">
            ' . Config::get('app.name') . ' - ' . __('kb.knowledge_base') . '
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
        $mpdf->WriteHTML($style, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Content

        // Make image paths relative
        $content = str_replace('src="/', 'src="./', $content);

        $mpdf->WriteHTML($content);

        $mpdf->Output($title . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }
}