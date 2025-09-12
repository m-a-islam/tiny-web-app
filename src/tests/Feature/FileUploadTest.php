<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JsonException;
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

    /**
     * Test that the upload fails if the file is larger than the validation limit.
     *
     * @return void
     */
    public function test_upload_fails_for_a_file_that_is_too_large()
    {
        $file = UploadedFile::fake()->create('large_spreadsheet.csv', 11 * 1024);

        $response = $this->post('/upload', [
            'spreadsheet' => $file,
        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors('spreadsheet');
    }


    /**
     * Test that a file at the maximum allowed size is uploaded successfully.
     *
     * @return void
     * @throws JsonException
     */
    public function test_a_file_at_the_maximum_size_is_uploaded_successfully()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('just_right_spreadsheet.csv', 10240);

        $header = 'name,type,location';
        $row1 = 'Boundary Test,Edge Case,Valid Location';
        $content = implode("\n", [$header, $row1]);
        file_put_contents($file->getPathname(), $content);


        $response = $this->post('/upload', [
            'spreadsheet' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/');
        $this->assertDatabaseHas('sample', [
            'name' => 'Boundary Test',
        ]);
    }
}
