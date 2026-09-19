<?php

namespace Modules\Barcode\Services;

class BarcodeGeneratorService
{
    /**
     * Generates an SVG string for a Code128 Barcode.
     */
    public function generateCode128Svg(string $code, int $height = 50, int $widthFactor = 2): string
    {
        $bars = $this->encodeCode128B($code);
        $totalWidth = strlen($bars) * $widthFactor;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$totalWidth.'" height="'.$height.'" viewBox="0 0 '.$totalWidth.' '.$height.'">';
        $svg .= '<rect width="100%" height="100%" fill="#ffffff"/>';

        $x = 0;
        for ($i = 0; $i < strlen($bars); $i++) {
            $w = $widthFactor;
            if ($bars[$i] === '1') {
                $svg .= '<rect x="'.$x.'" y="0" width="'.$w.'" height="'.$height.'" fill="#000000"/>';
            }
            $x += $w;
        }

        $svg .= '</svg>';

        return $svg;
    }

    /**
     * Generates a simple SVG representation for a QR code / Data matrix fallback.
     */
    public function generateQrSvg(string $text, int $size = 120): string
    {
        $matrixSize = 21;
        $cellSize = floor($size / $matrixSize);
        $svgSize = $cellSize * $matrixSize;

        $hash = md5($text);
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$svgSize.'" height="'.$svgSize.'" viewBox="0 0 '.$svgSize.' '.$svgSize.'">';
        $svg .= '<rect width="100%" height="100%" fill="#ffffff"/>';

        for ($row = 0; $row < $matrixSize; $row++) {
            for ($col = 0; $col < $matrixSize; $col++) {
                $isCornerFinder = ($row < 7 && $col < 7) || ($row < 7 && $col >= 14) || ($row >= 14 && $col < 7);
                $isBlack = false;

                if ($isCornerFinder) {
                    $r = $row % 7;
                    $c = $col % 7;
                    if ($row >= 14) {
                        $r = ($row - 14) % 7;
                    }
                    if ($col >= 14) {
                        $c = ($col - 14) % 7;
                    }
                    if ($r === 0 || $r === 6 || $c === 0 || $c === 6 || ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4)) {
                        $isBlack = true;
                    }
                } else {
                    $charIndex = ($row * $matrixSize + $col) % 32;
                    $isBlack = (hexdec($hash[$charIndex]) % 2) === 0;
                }

                if ($isBlack) {
                    $svg .= '<rect x="'.($col * $cellSize).'" y="'.($row * $cellSize).'" width="'.$cellSize.'" height="'.$cellSize.'" fill="#000000"/>';
                }
            }
        }

        $svg .= '</svg>';

        return $svg;
    }

    private function encodeCode128B(string $text): string
    {
        $patterns = [
            '11011001100', '11001101100', '11001100110', '10010011000', '10010001100',
            '10001001100', '10011001000', '10011000100', '10001100100', '11001001000',
            '11001000100', '11000100100', '10110011100', '10011011100', '10011001110',
            '10111001100', '10011101100', '10011100110', '11001110010', '11001011100',
            '11001001110', '11011100100', '11001110100', '11101101110', '11101001100',
            '11100101100', '11100100110', '11101100100', '11100110100', '11100110010',
            '11011011000', '11011000110', '11000110110', '10100011000', '10001011000',
            '10001000110', '10110001000', '10001101000', '10001100010', '11010001000',
            '11000101000', '11000100010', '10110111000', '10110001110', '10001101110',
            '10111011000', '10111000110', '10001110110', '11101110110', '11010001110',
            '11000101110', '11011101000', '11011100010', '11011101110', '11101011000',
            '11101000110', '11100010110', '11101101000', '11101100010', '11100011010',
            '11101111010', '11001000010', '11110001010', '10100110000', '10100001100',
            '10010110000', '10010000110', '10000101100', '10000100110', '10110010000',
            '10110000100', '10011010000', '10011000010', '10000110100', '10000110010',
            '11000010010', '11001010000', '11110111010', '11000010100', '10001111010',
            '10100111100', '10010111100', '10010011110', '10111100100', '10011110100',
            '10011110010', '11110100100', '11110010100', '11110010010', '11011011110',
            '11011110110', '11110110110', '10101111000', '10100011110', '10001011110',
            '10111101000', '10111100010', '11110101000', '11110100010', '10111011110',
            '10111101110', '11101011110', '11110101110', '11010000100', '11010010000',
            '11010011100',
        ];

        $startB = 104;
        $stop = '1100011101011';

        $checksum = $startB;
        $encoded = $patterns[$startB];

        for ($i = 0; $i < strlen($text); $i++) {
            $val = ord($text[$i]) - 32;
            if ($val < 0 || $val > 95) {
                $val = 0;
            }
            $checksum += $val * ($i + 1);
            $encoded .= $patterns[$val];
        }

        $checksumVal = $checksum % 103;
        $encoded .= $patterns[$checksumVal];
        $encoded .= $stop;

        return $encoded;
    }
}
