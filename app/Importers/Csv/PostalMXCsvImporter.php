<?php
namespace App\Importers\Csv;

use App\Models\ZipCode;
use App\Models\Settlement;
use Illuminate\Support\Str;
use App\Models\Municipality;
use App\Models\FederalEntity;
use App\Models\SettlementType;

final class PostalMXCsvImporter extends CsvImporter{
   
    /**
     * Csv Expected Headers
     *
     * @var array
     */
    protected $headers = [
        'd_codigo',
        'd_asenta',
        'd_tipo_asenta',
        'D_mnpio',
        'd_estado',
        'd_ciudad',
        'c_estado',
        'c_mnpio',
        'id_asenta_cpcons',
        'd_zona'
    ];
    
    /**
     * Import data
     *
     * @param string $path
     * @return void
     */
    public function run(string $path)
    {
        if($this->validateFile($path) == false){
            return [
                'success' => false,
                'errors' => $this->errors
            ];
        }
        
        $result = $this->convertData($path);

        if($result['success'] == false){
            return [
                'success' => false,
                'errors' => $this->errors
            ];
        }

        $this->processData($result['data']);
    }

    /**
     * Process CSV Data
     *
     * @param array $data
     * @return void
     */
    public function processData(array $data)
    {
        foreach ($data as $item) {
            $this->saveData($item);
        }
    }

    public function saveData(array $data)
    {
        $federalEntity = FederalEntity::firstOrCreate(
            ['key' => $data['c_estado']],
            [
                'name' => Str::upper($data['d_estado']),
            ]
        );

        $municipality = Municipality::firstOrCreate(
            ['key' => $data['c_estado']],
            [
                'name' => Str::upper($data['D_mnpio']),
            ]
        );

        $zipCode = ZipCode::firstOrCreate(
            ['zip_code' => $data['d_codigo']],
            [
                'locality' => Str::upper($data['d_ciudad']),
                'federal_entity_id' => $federalEntity->id,
                'municipality_id' => $municipality->id,
            ]
        );


        $settlementType = SettlementType::firstOrCreate(
            ['name' => $data['d_tipo_asenta']],
            []
        );

        $settlement = Settlement::firstOrCreate(
            ['key' => $data['id_asenta_cpcons']],
            [
                'name' =>  Str::upper($data['d_asenta']),
                'zone_type' => Str::upper($data['d_zona']),
                'settlement_type_id' => $settlementType->id,
                'zip_code_id' => $zipCode->id,
            ]
        );
    }
}