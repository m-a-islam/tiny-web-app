<?php

namespace Tests\Unit;

use App\Services\SpreadsheetImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class SpreadsheetImportServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the service correctly processes a valid spreadsheet.
     *
     * @return void
     */
    public function test_it_imports_data_from_a_valid_spreadsheet()
    {
        Storage::fake('local');

        $header = 'name,type,location';
        $row1 = 'Unit Test Name,Test Type,Test Location';
        $content = implode("\n", [$header, $row1]);
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);

        // We will still mock the Excel facade because we don't need to test the package itself.
        Excel::shouldReceive('toArray')
            ->once()
            ->andReturn([
                [
                    ['name', 'type', 'location'],
                    ['Unit Test Name', 'Test Type', 'Test Location'],
                ],
            ]);


        $importer = new SpreadsheetImportService();
        $importer->import($file);

        $this->assertDatabaseHas('sample', [
            'name' => 'Unit Test Name',
            'type' => 'Test Type',
            'location' => 'Test Location',
        ]);

        $this->assertDatabaseCount('sample', 1);
    }
}
