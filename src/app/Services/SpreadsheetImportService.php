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

        $data = Excel::toArray((object)[], $file);

        if (!empty($data) && count($data[0]) > 1) {
            $sheetData = $data[0];
            $header = array_map('trim', array_shift($sheetData));

            foreach ($sheetData as $row) {
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


}
