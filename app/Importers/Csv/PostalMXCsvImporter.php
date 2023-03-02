<?php
namespace App\Importers\Csv;

class PostalMXCsvImporter extends CsvImporter{
   
    /**
     * Csv Headers
     *
     * @var array
     */
    private $headers = [
        'd_codigo',
        'd_asenta',
        'd_tipo_asenta',
        'D_mnpio',
        'd_estado',
        'd_ciudad',
        'c_estado',
        'c_mnpio',
        'c_cve_ciudad'
    ];
    
    
}