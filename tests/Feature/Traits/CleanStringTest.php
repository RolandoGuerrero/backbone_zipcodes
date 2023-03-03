<?php

namespace Tests\Feature\Traits;

use App\Models\FederalEntity;
use App\Models\Municipality;
use App\Models\ZipCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CleanStringTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     */
    public function test_the_cleanable_attributes_save_cleaned(): void
    {
       $zipCode = ZipCode::factory([
        'locality' => 'MÉXICO'
       ])
       ->for(FederalEntity::factory([
        'name' => 'MÉXICO'
       ]))
       ->for(Municipality::factory([
        'name' => 'MÉXICO'
       ])->for(FederalEntity::factory())
       )->create();

       $this->assertSame('MEXICO', $zipCode->locality);
       $this->assertSame('MEXICO', $zipCode->federalEntity->name);
       $this->assertSame('MEXICO', $zipCode->municipality->name);
    }
}
