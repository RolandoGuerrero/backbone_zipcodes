<?php
namespace App\Actions\ZipCode\Contracts;

interface CsvImporterContract {
    
    public function run(string $path);
   
    public function validateFile(string $path) :  bool;

    public function convertData(string $path) :  array;

    public function validateHeaders(string $path) :  bool;
}