<?php

namespace Tests\Feature\Importers;

use App\Importers\Csv\PostalMXCsvImporter;
use Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostalMxCsvImporterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_can_import_csv(): void
    {
        $filePath = storage_path('testing/test_csv.csv');

        $result = (new PostalMXCsvImporter)->run($filePath);
        
        $this->assertTrue($result['success']);
        $this->assertDatabaseCount('federal_entities', 1);
        $this->assertDatabaseCount('municipalities', 1);
        $this->assertDatabaseCount('zip_codes', 1);
        $this->assertDatabaseCount('settlement_types', 1);
        $this->assertDatabaseCount('settlements', 1);
    }

    
    public function test_cant_import_unexisted_csv(): void
    {
        $filePath = storage_path('testing/no_csv.csv');

        $result = (new PostalMXCsvImporter)->run($filePath);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('file', $result['errors']);
        $this->assertSame('The file does not exists', $result['errors']['file'][0]);
    }

    public function test_cant_import_no_csv_extension(): void
    {
        $filePath = storage_path('testing/test_csv.txt');

        $result = (new PostalMXCsvImporter)->run($filePath);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('file_type', $result['errors']);
        $this->assertSame('The file extension is incorrect', $result['errors']['file_type'][0]);
    }

    public function test_cant_import_invalid_csv(): void
    {
        $filePath = storage_path('testing/invalid.csv');

        $result = (new PostalMXCsvImporter)->run($filePath);

        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('headers', $result['errors']);
        $this->assertSame('d_codigo is not in the csv', $result['errors']['headers'][0]);
    }
}
