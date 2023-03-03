<?php
namespace App\Importers\Csv;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class CsvImporter implements CsvImporterContract{

    /**
     * Csv Headers
     *
     * @var array
     */
    protected $headers = [];
    
    /**
     * Errors
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Run import
     *
     * @param string $path
     * @return void
     */
    public function run(string $path){

    }
    
    /**
     * Validate if file exis and if is an csv
     *
     * @param string $path
     * @return boolean
     */
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

    /**
     * Convert file csv to array
     *
     * @param string $path
     * @return array
     */
    public function convertData(string $path) :  array{
        $csvLines = [];
        $file = fopen($path, 'r');

        while (($line = fgetcsv($file)) !== FALSE) {
            $csvLines[] = $line;
        }
        
        if($this->validateHeaders($csvLines[0]) == false){
            return [
                'data' => [],
                'success' => false,
            ];
        }

        $headers = $csvLines[0];
        array_shift($csvLines);
        $data = [];

        foreach ($csvLines as $csvLine) {
            $dataLine = [];

            foreach($headers as $key => $name){
                $dataLine[$name] = isset($csvLine[$key]) ?  $csvLine[$key] : null;
            }

            $data[] = $dataLine;
        }

        return [ 
            'data' => $data,
            'success' => true,
        ];
    }

    /**
     * Validate if expected headers exists in the csv
     *
     * @param array $headers
     * @return boolean
     */
    public function validateHeaders(array $headers) :  bool {
        $hasErrors = false;
       
        foreach ($this->headers as $header) {
            if(in_array($header, $headers) == false){
                $hasErrors = true;

                $this->errors['headers'][] = "{$header} is not in the csv";
            }

        }

        return !$hasErrors;
    }
}