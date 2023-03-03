<?php

namespace Tests\Feature\Actions;

use App\Actions\ZipCode\GetZipCode;
use App\Models\FederalEntity;
use App\Models\Municipality;
use App\Models\Settlement;
use App\Models\SettlementType;
use App\Models\ZipCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetZipCodeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_can_get_zip_code_data(): void
    {
        $federalEntity = FederalEntity::factory();

        $zipCode = ZipCode::factory()
        ->for($federalEntity)
        ->for(
            Municipality::factory()
            ->for($federalEntity)
        )
        ->has(
            Settlement::factory()
            ->for(SettlementType::factory())
        )->create();

        $result = (new GetZipCode)->run($zipCode->zip_code);

        $this->assertInstanceOf(ZipCode::class, $result);
        $this->assertSame($zipCode->id, $result->id);
        $this->assertSame($zipCode->federalEntity->id, $result->federalEntity->id);
        $this->assertSame($zipCode->municipality->id, $result->municipality->id);
        $this->assertSame($zipCode->settlements[0]->id, $result->settlements[0]->id);
        $this->assertSame($zipCode->settlements[0]->settlementType->id, $result->settlements[0]->settlementType->id);
    }

    public function test_cant_get_unexisted_zip_code(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $zipCode = '000000';

        $result = (new GetZipCode)->run($zipCode);
    }
}
