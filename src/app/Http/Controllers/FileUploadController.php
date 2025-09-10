<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Maatwebsite\Excel\Facades\Excel;

class FileUploadController extends Controller
{
    public function index(): Factory|View
    {
        $samples = Sample::paginate(5);
        return view('upload', ['samples' => $samples]);
    }
    public function upload(Request $request): Redirector|RedirectResponse
    {

        $request->validate([
            'spreadsheet' => 'required|file|mimes:xlsx,xls,csv,txt'
        ]);
        $projectName = config('app.name');
        $file = $request->file('spreadsheet');

        $timestamp = now()->format('Y-m-d_H-i-s');

        $extension = $file->getClientOriginalExtension();

        $newFilename = "{$projectName}_{$timestamp}.{$extension}";

        $path = $file->storeAs('uploads', $newFilename);
        $data = Excel::toArray((object)[], $file);


        if (!empty($data) && count($data[0]) > 1) {
            $sheetData = $data[0];

            $header = array_shift($sheetData);
            $header = array_map('trim', $header);
            foreach ($sheetData as $row) {
                $rowData = array_combine($header, $row);

                Sample::create([
                    'name' => $rowData['name'],
                    'type' => $rowData['type'],
                    'location' => $rowData['location']
                ]);
            }
        }
        return redirect('/')->with('success', 'File uploaded and data imported successfully!');
    }

}
