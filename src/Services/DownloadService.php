<?php

namespace Jeffpereira\RealEstate\Services;

use ZipArchive;

class DownloadService
{
    private const PATH_ZIP = 'app/zip-downloads';

    public function createZipDownload(array $wayFiles): string
    {
        $zip = new ZipArchive();
        $fileNameZip = self::PATH_ZIP . '/files_' . time() . '.zip';
        $this->checkAndCreateDirectoryZip();

        if (true === ($zip->open(storage_path($fileNameZip), ZipArchive::CREATE|ZipArchive::OVERWRITE))) {
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
        if (!file_exists(storage_path(self::PATH_ZIP))) {
            mkdir(storage_path(self::PATH_ZIP), 0755, true);
        }
    }
}
