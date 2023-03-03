<?php
namespace App\Actions\ZipCode;

use App\Models\ZipCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetZipCode{

    /**
     * Get the given zipcode info
     *
     * @param string $zipCode
     * @return App\Models\ZipCode 
     */
    public function run(string $zipCode) : ZipCode
    {
        // $cachedZipcode = Cache::get($zipCode);

        // if($cachedZipcode){
        //     return $cachedZipcode;
        // }

        $zipCode = ZipCode::where('zip_code', $zipCode)
        ->with('federalEntity', 'municipality', 'settlements.settlementType')
        ->first();

        if(!$zipCode){
            throw new ModelNotFoundException("Zip code not found");
        }

        Cache::put(
            $zipCode->zip_code, 
            $zipCode,
            now()->addDay()
        );

        return $zipCode;
    }

}