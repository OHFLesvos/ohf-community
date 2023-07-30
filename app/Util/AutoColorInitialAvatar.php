<?php

namespace App\Util;

use LasseRafn\InitialAvatarGenerator\InitialAvatar;

class AutoColorInitialAvatar extends InitialAvatar
{
    /**
     * Automatically set a font and/or background color based on the supplied name.
     *
     * @return $this
     */
    public function autoColor(bool $foreground = true, bool $background = true, int $saturation = 85, int $luminance = 60)
    {
        $hue = (crc32($this->name) % 360) / 360;
        $saturation /= 100;
        $luminance /= 100;

        $this->bgColor = $this->convertHSLtoRGB($hue, $saturation, $luminance);
        $this->fontColor = $this->getContrastColor($this->bgColor);

        return $this;
    }

    protected function convertHSLtoRGB($h, $s, $l, $toHex = true)
    {
        assert((0 <= $h) && ($h <= 1));

        $red = $l;
        $green = $l;
        $blue = $l;

        $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
        if ($v > 0) {
            $m = $l + $l - $v;
            $sv = ($v - $m) / $v;
            $h *= 6.0;
            $sextant = floor($h);
            $fract = $h - $sextant;
            $vsf = $v * $sv * $fract;
            $mid1 = $m + $vsf;
            $mid2 = $v - $vsf;

            switch ($sextant) {
                case 0:
                    $red = $v;
                    $green = $mid1;
                    $blue = $m;
                    break;
                case 1:
                    $red = $mid2;
                    $green = $v;
                    $blue = $m;
                    break;
                case 2:
                    $red = $m;
                    $green = $v;
                    $blue = $mid1;
                    break;
                case 3:
                    $red = $m;
                    $green = $mid2;
                    $blue = $v;
                    break;
                case 4:
                    $red = $mid1;
                    $green = $m;
                    $blue = $v;
                    break;
                case 5:
                    $red = $v;
                    $green = $m;
                    $blue = $mid2;
                    break;
            }
        }

        $red = (int) round($red * 255, 0);
        $green = (int) round($green * 255, 0);
        $blue = (int) round($blue * 255, 0);

        if ($toHex) {
            $red = ($red < 15) ? '0'.dechex($red) : dechex($red);
            $green = ($green < 15) ? '0'.dechex($green) : dechex($green);
            $blue = ($blue < 15) ? '0'.dechex($blue) : dechex($blue);

            return "#{$red}{$green}{$blue}";
        } else {
            return ['red' => $red, 'green' => $green, 'blue' => $blue];
        }
    }

    /**
     * Get contrasting foreground color for autoColor background.
     */
    protected function getContrastColor($hexColor)
    {
        // hexColor RGB
        $R1 = hexdec(substr($hexColor, 1, 2));
        $G1 = hexdec(substr($hexColor, 3, 2));
        $B1 = hexdec(substr($hexColor, 5, 2));

        // Black RGB
        $blackColor = '#000000';
        $R2BlackColor = hexdec(substr($blackColor, 1, 2));
        $G2BlackColor = hexdec(substr($blackColor, 3, 2));
        $B2BlackColor = hexdec(substr($blackColor, 5, 2));

        // Calc contrast ratio
        $L1 = 0.2126 * pow($R1 / 255, 2.2) +
            0.7152 * pow($G1 / 255, 2.2) +
            0.0722 * pow($B1 / 255, 2.2);

        $L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
            0.7152 * pow($G2BlackColor / 255, 2.2) +
            0.0722 * pow($B2BlackColor / 255, 2.2);

        $contrastRatio = 0;
        if ($L1 > $L2) {
            $contrastRatio = (int) (($L1 + 0.05) / ($L2 + 0.05));
        } else {
            $contrastRatio = (int) (($L2 + 0.05) / ($L1 + 0.05));
        }

        // If contrast is more than 5, return black color
        if ($contrastRatio > 5) {
            return '#000000';
        } else {
            // if not, return white color.
            return '#FFFFFF';
        }
    }
}
