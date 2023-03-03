<?php

namespace App\Http\Controllers;

use App\Actions\ZipCode\GetZipCode;
use App\Http\Resources\ZipCodeResource;

class GetZipcodeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        string $zipCode,
        GetZipCode $getZipCode)
    {
        $zipCode = $getZipCode->run($zipCode);

       return new ZipCodeResource($zipCode);
    }
}
