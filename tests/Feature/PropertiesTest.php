<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_owner_has_access_to_properties_feature()
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $response = $this->actingAs($owner)->getJson('/api/owner/properties');

        $response->assertStatus(200);
    }

    public function test_user_does_not_have_access_to_properties_feature()
    {
        $user = User::factory()->create(['role_id' => Role::ROLE_USER]);
        $response = $this->actingAs($user)->getJson('/api/owner/properties');

        $response->assertStatus(403);
    }

    public function test_property_owner_can_add_property()
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $response = $this->actingAs($owner)->postJson('/api/owner/properties', [
            'name'             => '::property::',
            'city_id'          => City::value('id'), // get value of the first record
            'address_street'   => '::street::',
            'address_postcode' => '',
        ]);

        $response->assertSuccessful();
        $response->assertJsonFragment(['name' => '::property::']);
    }

    /**
     * @dataProvider provideBadPropertyData
     */
    public function test_add_property_validation($missing, $data)
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $response = $this->actingAs($owner)->postJson('/api/owner/properties', $data);

        $response->assertJsonValidationErrors([$missing]);
    }

    public function test_user_can_not_add_property()
    {
        $user = User::factory()->create(['role_id' => Role::ROLE_USER]);
        $response = $this->actingAs($user)->postJson('/api/owner/properties', [
            'name'             => '::property::',
            'city_id'          => City::value('id'), // get value of the first record
            'address_street'   => '::street::',
            'address_postcode' => '',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Note: PHPUnit will call the data provider methods before running any tests
     * Hence it is not possible to access Model Factory by default.
     * https://stackoverflow.com/a/45148267
     */
    public function provideBadPropertyData()
    {
        $property = [
            'name'             => '::property::',
            'city_id'          => 1,
            'address_street'   => '::street::',
            'address_postcode' => '',
        ];

        return [
            'missing name' => [
                'name',
                [
                    ...$property,
                    'name' => null
                ]
            ],
            'missing city_id' => [
                'city_id',
                [
                    ...$property,
                    'city_id' => null
                ]
            ],
            'non-existing city_id' => [
                'city_id',
                [
                    ...$property,
                    'city_id' => 999
                ]
            ],
            'missing address_street' => [
                'address_street',
                [
                    ...$property,
                    'address_street' => null
                ]
            ],
        ];
    }
}
