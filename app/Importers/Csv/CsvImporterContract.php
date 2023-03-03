<?php
namespace App\Importers\Csv;

interface CsvImporterContract {
    
    public function run(string $path);
   
    public function validateFile(string $path) :  bool;

    public function convertData(string $path) :  array;

    public function validateHeaders(array $headers) :  bool;
}