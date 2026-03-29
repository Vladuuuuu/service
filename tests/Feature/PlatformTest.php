<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformTest extends TestCase
{
    use RefreshDatabase;

    /** Landing page se încarcă corect */
    public function test_landing_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ServiceAuto');
    }

    /** Clientul autentificat poate accesa dashboard-ul */
    public function test_client_can_access_dashboard(): void
    {
        $user = User::create([
            'name' => 'Test Client',
            'email' => 'client@test.com',
            'password' => bcrypt('123456'),
            'role' => 'client',
        ]);

        $response = $this->actingAs($user)->get('/client/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Welcome');
    }

    /** Clientul poate vedea lista de service-uri */
    public function test_services_page_loads(): void
    {
        $serviceUser = User::create([
            'name' => 'Service User',
            'email' => 'service@test.com',
            'password' => bcrypt('123456'),
            'role' => 'service',
        ]);

        Service::create([
            'user_id' => $serviceUser->id,
            'name' => 'AutoPro Test',
            'address' => 'Str. Test 1',
            'city' => 'Focșani',
            'rating' => 4.5,
        ]);

        $response = $this->get('/services');
        $response->assertStatus(200);
        $response->assertSee('AutoPro Test');
    }
}
