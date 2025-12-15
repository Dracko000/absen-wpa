<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IconController extends Controller
{
    public function generateIcon($size)
    {
        $sizes = [
            72 => [72, 72],
            96 => [96, 96],
            128 => [128, 128],
            144 => [144, 144],
            152 => [152, 152],
            192 => [192, 192],
            384 => [384, 384],
            512 => [512, 512]
        ];

        if (!isset($sizes[$size])) {
            abort(404);
        }

        $width = $sizes[$size][0];
        $height = $sizes[$size][1];

        // Create image
        $image = imagecreate($width, $height);

        // Define colors
        $background = imagecolorallocate($image, 59, 130, 246); // blue-500
        $textColor = imagecolorallocate($image, 255, 255, 255); // white

        // Fill background
        imagefill($image, 0, 0, $background);

        // Add text (optional)
        $text = $size . 'x' . $size;
        $font = 5; // Built-in font
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;

        imagestring($image, $font, $x, $y, $text, $textColor);

        // Output image
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);

        exit;
    }
}
