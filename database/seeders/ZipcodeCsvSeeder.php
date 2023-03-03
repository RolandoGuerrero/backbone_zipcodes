<?php

namespace Database\Seeders;

use App\Importers\Csv\PostalMXCsvImporter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ZipcodeCsvSeeder extends Seeder
{   
    /** 
     *
     * @var CsvImporter
     */
    public $csvImporter;

    public function __construct(PostalMXCsvImporter $csvImporter)
    {
        $this->csvImporter = $csvImporter;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = File::allFiles(storage_path('seeders'));

        foreach($files as $file){   
            $this->csvImporter->run($file->getPathName());
        }
    }
}
