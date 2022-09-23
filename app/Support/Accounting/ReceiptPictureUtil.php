<?php

namespace App\Support\Accounting;

use Gumlet\ImageResize;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Org_Heigl\Ghostscript\Ghostscript;
use Spatie\PdfToImage\Pdf;

class ReceiptPictureUtil
{
    private const RECEIPT_PICTURE_PATH = 'public/accounting/receipts';

    public static function addReceiptPicture(UploadedFile $file): string
    {
        $storedFile = $file->store(self::RECEIPT_PICTURE_PATH);
        $storedFilePath = Storage::path($storedFile);
        $thumbSize = config('accounting.thumbnail_size');
        $maxImageDimension = config('accounting.max_image_size');

        if (Str::startsWith($file->getMimeType(), 'image/')) {
            self::createThumbnail($storedFilePath, $thumbSize);
            self::resizeImage($storedFilePath, $maxImageDimension);
        } elseif ($file->getMimeType() == 'application/pdf') {
            self::createPdfThumbnail($storedFilePath, $thumbSize);
        }

        return $storedFile;
    }

    private static function createThumbnail($path, $dimensions): void
    {
        $thumbPath = thumb_path($path);
        $image = new ImageResize($path);
        $image->crop($dimensions, $dimensions);
        $image->save($thumbPath);
    }

    private static function resizeImage($path, $dimensions): void
    {
        $image = new ImageResize($path);
        $image->resizeToBestFit($dimensions, $dimensions);
        $image->save($path);
    }

    private static function createPdfThumbnail($path, $dimensions): void
    {
        $thumbPath = thumb_path($path, 'jpeg');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            Ghostscript::setGsPath(config('accounting.gs_path'));
        }
        $pdf = new Pdf($path);
        $pdf->saveImage($thumbPath);
        $image = new ImageResize($thumbPath);
        $image->crop($dimensions, $dimensions);
        $image->save($thumbPath);
    }

    public static function rotateReceiptPicture(string $picture, string $direction): void
    {
        self::rotatePicture(Storage::path($picture), $direction);
        self::rotatePicture(Storage::path(thumb_path($picture)), $direction);
    }

    private static function rotatePicture(string $path, string $direction): void
    {
        $img = Image::make($path);
        $img->rotate($direction == 'left' ? 90 : -90);
        $img->save($path);
    }

    public static function deleteReceiptPictures(?array $pictures): void
    {
        if (empty($pictures)) {
            return;
        }

        foreach ($pictures as $path) {
            self::deleteReceiptPicture($path);
        }
    }

    public static function deleteReceiptPicture(string $path): void
    {
        Storage::delete($path);
        Storage::delete(thumb_path($path));
        Storage::delete(thumb_path($path, 'jpeg'));
    }
}
