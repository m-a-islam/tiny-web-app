<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Sample;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

/**
* Test that the main page loads successfully.
*
* @return void
*/
public function test_main_page_loads_correctly()
{
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Upload Your Sample Data');
}

/**
* Test that a valid spreadsheet can be uploaded and its data is stored.
*
* @return void
*/
public function test_a_valid_spreadsheet_can_be_uploaded()
{
    Storage::fake('local');

    $header = 'name,type,location';
    $row1 = 'Test Name 1,Type A,Location X';
    $row2 = 'Test Name 2,Type B,Location Y';
    $content = implode("\n", [$header, $row1, $row2]);

    $file = UploadedFile::fake()->createWithContent('test_data.csv', $content);

    $response = $this->post('/upload', [
    'spreadsheet' => $file,
    ]);

    $response->assertRedirect('/');

    $this->assertDatabaseHas('sample', [
    'name' => 'Test Name 1',
    'type' => 'Type A',
    'location' => 'Location X',
    ]);

    $this->assertDatabaseCount('sample', 2);

    $files = Storage::disk('local')->files('uploads');
    $this->assertCount(1, $files);
    $this->assertStringContainsString('tiny-web-app', $files[0]);
    }


    /**
     * Test that the upload fails if no file is provided.
     *
     * @return void
     */
    public function test_upload_fails_if_no_file_is_provided()
    {
        $response = $this->post('/upload', [
            'spreadsheet' => null,
        ]);

        $response->assertSessionHasErrors('spreadsheet');
        $response->assertStatus(302);
    }

    /**
     * Test that the upload fails if the file is not a valid spreadsheet type.
     *
     * @return void
     */
    public function test_upload_fails_for_invalid_file_type()
    {
        $file = UploadedFile::fake()->image('not_a_spreadsheet.jpg');

        $response = $this->post('/upload', [
            'spreadsheet' => $file,
        ]);

        $response->assertSessionHasErrors('spreadsheet');
    }

    /**
     * Test that an empty or header-only spreadsheet is handled gracefully.
     *
     * @return void
     */
    public function test_an_empty_spreadsheet_is_handled_gracefully()
    {
        $header = 'name,type,location';
        $file = UploadedFile::fake()->createWithContent('empty.csv', $header);

        $response = $this->post('/upload', [
            'spreadsheet' => $file,
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseCount('sample', 0);
    }
}
