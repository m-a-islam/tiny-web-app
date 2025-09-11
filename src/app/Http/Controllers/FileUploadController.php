<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpreadsheetRequest;
use App\Models\Sample;
use App\Services\SpreadsheetImportService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class FileUploadController extends Controller
{
    public function index(Request $request): Factory|View
    {
        $allowedPerPage = [10, 25, 50, 100];

        $recsPerPage = $request->query('per_page', $allowedPerPage[0]);
        if (!in_array($recsPerPage, $allowedPerPage)) {
            $recsPerPage = $allowedPerPage[0];
        }

        $samples = Sample::latest()->paginate($recsPerPage);

        $samples->appends(['per_page' => $recsPerPage]);
        return view('upload', [
            'samples' => $samples,
            'recsPerPage' => $recsPerPage
        ]);
    }


    /**
     * Handle the file upload and store the data in the database.
     *
     * @param StoreSpreadsheetRequest $request
     * @param SpreadsheetImportService $importer
     * @return Redirector|RedirectResponse
     */
    public function upload(StoreSpreadsheetRequest $request, SpreadsheetImportService $importer): Redirector|RedirectResponse
    {
        $file = $request->file('spreadsheet');

        $importer->import($file);

        return redirect('/')->with('success', 'File uploaded and data imported successfully!');
    }


}
