<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AttendanceAppTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the application loads correctly
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test user roles are properly set
     */
    public function test_user_roles_functionality(): void
    {
        $superAdmin = User::factory()->create(['role' => 'superadmin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($superAdmin->isSuperAdmin());
        $this->assertFalse($superAdmin->isAdmin());
        $this->assertFalse($superAdmin->isStudent());

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isSuperAdmin());
        $this->assertFalse($admin->isStudent());

        $this->assertTrue($user->isStudent());
        $this->assertFalse($user->isSuperAdmin());
        $this->assertFalse($user->isAdmin());
    }

    /**
     * Test PWA manifest is accessible
     */
    public function test_pwa_manifest_is_accessible(): void
    {
        $response = $this->get('/manifest.json');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
    }

    /**
     * Test service worker is accessible
     */
    public function test_service_worker_is_accessible(): void
    {
        $response = $this->get('/sw.js');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/javascript');
    }

    /**
     * Test icon generator
     */
    public function test_icon_generator_works(): void
    {
        $response = $this->get('/icon/192');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'image/png');
    }
}
