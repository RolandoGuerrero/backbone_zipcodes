<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\ZipCode;
use App\Models\Settlement;
use Mockery\MockInterface;
use App\Models\Municipality;
use App\Models\FederalEntity;
use App\Models\SettlementType;
use App\Actions\ZipCode\GetZipCode;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetZipCodeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_can_get_zip_code(): void
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
        $response = $this->get('/');


        $zipCode->load('federalEntity', 'municipality', 'settlements.settlementType');

        $this->mock(GetZipCode::class, function (MockInterface $mock) use($zipCode) {
            $mock
            ->shouldReceive('run')
            ->once()
            ->with($zipCode->zip_code)
            ->andReturn($zipCode);       
        });
        
        $response = $this->json('get', route('zip_code.show', $zipCode->zip_code));
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'zip_code' => $zipCode->zip_code,
            'locality' => $zipCode->locality,
            'federal_entity' => [
                'key' => $zipCode->federalEntity->key,
                'name' => $zipCode->federalEntity->name,
                'code' => $zipCode->federalEntity->code,
            ],
            'municipality' => [
                'key' => $zipCode->municipality->key,
                'name' => $zipCode->municipality->name,
            ],
            'settlements' => [
                [
                    'key' => $zipCode->settlements[0]->key,
                    'name' => $zipCode->settlements[0]->name,
                    'zone_type' => $zipCode->settlements[0]->zone_type,
                    'settlement_type' => [
                        'name' => $zipCode->settlements[0]->settlementType->name,
                    ]
                ]
            ]
        ]);
    }

    public function test_cant_get_unexisted_zip_code(): void
    {
        $zipCode = '0000';
        $response = $this->json('get', route('zip_code.show', $zipCode));
        $response->assertStatus(404);
    }
}
