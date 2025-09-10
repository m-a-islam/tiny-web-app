<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpreadsheetRequest;
use App\Models\Sample;
use App\Services\SpreadsheetImportService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class FileUploadController extends Controller
{
    public const RECS_PER_PAGE = 5;

    public function index(): Factory|View
    {
        $samples = Sample::paginate(self::RECS_PER_PAGE);
        return view('upload', ['samples' => $samples]);
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
