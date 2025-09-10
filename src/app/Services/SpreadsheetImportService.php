<?php

namespace App\Services;

use App\Models\Sample;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class SpreadsheetImportService
{
    /**
     * Handle the import of a spreadsheet file.
     *
     * @param UploadedFile $file
     * @return void
     */
    public function import(UploadedFile $file): void
    {
        $this->storeFile($file);

        $data = Excel::toArray((object)[], $file);

        if (!empty($data) && count($data[0]) > 1) {
            $sheetData = $data[0];
            $header = array_map('trim', array_shift($sheetData));

            foreach ($sheetData as $row) {
                // Skip empty rows
                if (empty(implode('', $row))) continue;

                $rowData = array_combine($header, $row);

                Sample::create([
                    'name' => $rowData['name'] ?? null,
                    'type' => $rowData['type'] ?? null,
                    'location' => $rowData['location'] ?? null,
                ]);
            }
        }
    }

    /**
     * Store the file with a project-specific name.
     *
     * @param UploadedFile $file
     * @return string The path where the file was stored.
     */
    protected function storeFile(UploadedFile $file): string
    {
        $projectName = config('app.name', 'tiny-web-app');
        $timestamp = now()->format('Y-m-d_H-i-s');
        $extension = $file->getClientOriginalExtension();
        $newFilename = "{$projectName}_{$timestamp}.{$extension}";

        return $file->storeAs('uploads', $newFilename);
    }
}
