<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Writer;

trait CsvOperations
{
    /**
     * Export data to CSV
     *
     * @param Collection|array $data
     * @param array $headers
     * @param string $filename
     * @return Response
     */
    public function exportToCsv($data, array $headers, string $filename): Response
    {
        $callback = function() use ($data, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($data as $row) {
                if ($row instanceof \Illuminate\Database\Eloquent\Model) {
                    $row = $row->toArray();
                }
                fputcsv($file, array_values($row));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Import data from CSV
     *
     * @param string $filePath
     * @param array $columnMap Key-value pairs where key is CSV header and value is database column
     * @param bool $hasHeader Whether CSV has header row
     * @param string $delimiter CSV delimiter
     * @return array
     */
    public function importFromCsv(
        string $filePath,
        array $columnMap = [],
        bool $hasHeader = true,
        string $delimiter = ','
    ): array {
        $csv = Reader::createFromPath($filePath);
        $csv->setDelimiter($delimiter);

        if ($hasHeader) {
            $csv->setHeaderOffset(0);
        }

        $records = [];
        $headers = $hasHeader ? $csv->getHeader() : [];
        
        foreach ($csv->getRecords($headers) as $record) {
            $mappedRecord = [];
            
            foreach ($record as $key => $value) {
                $dbColumn = $columnMap[$key] ?? $key;
                $mappedRecord[$dbColumn] = $value;
            }
            
            $records[] = $mappedRecord;
        }

        return $records;
    }

    /**
     * Validate CSV structure
     *
     * @param string $filePath
     * @param array $requiredHeaders
     * @param string $delimiter
     * @return array
     */
    public function validateCsvStructure(
        string $filePath,
        array $requiredHeaders,
        string $delimiter = ','
    ): array {
        $csv = Reader::createFromPath($filePath);
        $csv->setDelimiter($delimiter);
        $csv->setHeaderOffset(0);

        $headers = $csv->getHeader();
        $missingHeaders = array_diff($requiredHeaders, $headers);
        
        return [
            'isValid' => empty($missingHeaders),
            'missingHeaders' => $missingHeaders,
            'headers' => $headers
        ];
    }
} 