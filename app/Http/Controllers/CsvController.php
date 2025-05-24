<?php

namespace App\Http\Controllers;

use App\Traits\CsvOperations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class CsvController extends Controller
{
    use CsvOperations;

    /**
     * The required headers for CSV import
     *
     * @return array
     */
    abstract protected function getRequiredHeaders(): array;

    /**
     * The column mapping for CSV import
     *
     * @return array
     */
    abstract protected function getColumnMap(): array;

    /**
     * Process a single record from CSV
     *
     * @param array $record
     * @return mixed
     */
    abstract protected function processRecord(array $record);

    /**
     * Handle CSV import
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:10240' // max 10MB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // Validate CSV structure
        $validation = $this->validateCsvStructure($path, $this->getRequiredHeaders());
        
        if (!$validation['isValid']) {
            return back()->withErrors([
                'csv_file' => 'Missing required columns: ' . implode(', ', $validation['missingHeaders'])
            ])->withInput();
        }

        try {
            // Import and process records
            $records = $this->importFromCsv($path, $this->getColumnMap());
            $processed = 0;
            $failed = 0;

            foreach ($records as $record) {
                try {
                    $this->processRecord($record);
                    $processed++;
                } catch (\Exception $e) {
                    \Log::error('CSV Import Error: ' . $e->getMessage(), [
                        'record' => $record,
                        'error' => $e->getMessage()
                    ]);
                    $failed++;
                }
            }

            $message = sprintf(
                'Successfully processed %d records. %d records failed.',
                $processed,
                $failed
            );

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error processing CSV file: ' . $e->getMessage());
        }
    }
} 