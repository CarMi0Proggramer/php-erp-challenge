<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_access_users_page()
    {
        /** @var User $user */
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.users'));

        $response->assertOk();
    }

    public function test_non_admins_cannot_access_users_page()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.users'));

        $response->assertRedirect(route('dashboard'))
            ->assertSessionHas(
                'error',
                'Você não tem permissão para entrar nesta seção.'
            );

        $this->followRedirects($response)
            ->assertInertia(function (Assert $page) {
                $page->where(
                    'flash.error',
                    'Você não tem permissão para entrar nesta seção.'
                );
            });
    }
}
