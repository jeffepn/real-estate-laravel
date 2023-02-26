<?php

namespace Jeffpereira\RealEstate\Services;

use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DownloadService
{
    private const PATH_ZIP = 'zip-downloads';

    public function createZipDownload(array $wayFiles, $deleteFile = true): string
    {
        $zip = new ZipArchive();
        $fileNameZip = sprintf("app/%s/files_%d.zip", self::PATH_ZIP, time());
        $this->cleanDirectory();
        $this->checkAndCreateDirectoryZip();

        if (true === ($zip->open(storage_path($fileNameZip), ZipArchive::CREATE | ZipArchive::OVERWRITE))) {
            foreach ($wayFiles as $way) {
                $relativeName = basename($way);
                $zip->addFile($way, $relativeName);
            }
            $zip->close();
        }

        return storage_path($fileNameZip);
    }

    private function checkAndCreateDirectoryZip(): void
    {
        Storage::makeDirectory(self::PATH_ZIP);
    }

    private function cleanDirectory(): void
    {
        Storage::delete(Storage::allFiles(self::PATH_ZIP));
    }
}
