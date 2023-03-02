<?php
namespace App\Importers\Csv;

use Illuminate\Support\Facades\File;
use App\Actions\ZipCode\Contracts\CsvImporterContract;

class CsvImporter implements CsvImporterContract{

    /**
     * Csv Headers
     *
     * @var array
     */
    private $headers = [];
    
    /**
     * Errors
     *
     * @var array
     */
    private $errors = [];

    public function run(string $path){

    }
   
    public function validateFile(string $path):  bool { 
        
        if(File::exists($path) == false){
            $this->errors['file'] = ['The file does not exists']; 
            return false;
        }


        if(File::extension($path) !== 'csv'){
            $this->errors['file_type'] = ['The file extension is incorrect']; 
            return false;
        }

        
        return true;
    }

    public function convertData(string $path) :  array{
        $file = fopen($path, 'r');
        $csv = [];

        while (($line = fgetcsv($file)) !== FALSE) {
            //$line is an array of the csv elements
            $csv[] = $line;
        }

        dd($csv);
        return [];
    }

    public function validateHeaders(string $path) :  bool {
        return false;
    }
}