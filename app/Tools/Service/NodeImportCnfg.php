<?php

namespace App\Tools\Service;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class NodeImportCnfg
{
    private array $sheets;

    public function toArray($file): array|null
    {
        $inputType = IOFactory::identify($file);
        $reader = IOFactory::createReader($inputType);
        $reader->setReadDataOnly(true);

        if ($this->sheets != null) {
            $reader->setLoadSheetsOnly($this->sheets);
        }

        try {
            $content = $reader->load($file);
            $data = [];
            foreach ($this->sheets as $sheet) {
                $sheetContent = $content->getSheetByName($sheet);
                $rows = $sheetContent->toArray();

                $data[$sheet] = $this->getSortedRows($rows);
            }
            return $data;
        } catch (Exception $e) {
            echo $e;
        }
        return null;
    }

    public function setSheets($sheets): void
    {
        $this->sheets = $sheets;
    }

    public function getSortedRows(array $data): array
    {
        $sortedRows = [];
        $lastColumn = 0;
        foreach ($data[0] as $cell) {
            if ($cell == null) {
                break;
            }
            $lastColumn = array_search($cell, $data[0]);
        }

        foreach ($data as $row) {
            $sortedRow = [];
            for ($i = 0; $i <= $lastColumn; $i++) {
                $sortedRow[] = $row[$i];
            }
            $sortedRows[] = $sortedRow;
        }
        return $sortedRows;
    }
}

