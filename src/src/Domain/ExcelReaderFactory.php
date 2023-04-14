<?php

namespace App\Domain;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ExcelReaderFactory
{
    /**
     * @param string $path
     * @return Xlsx
     */
    public function createForPath($path): Xlsx
    {
        return \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($path);
    }
}