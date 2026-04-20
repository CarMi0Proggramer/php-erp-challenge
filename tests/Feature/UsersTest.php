<?php

namespace Tests\Feature;

use App\Enums\Role;
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
            ->get(route('users'));

        $response->assertOk();
    }

    public function test_non_admins_cannot_access_users_page()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('users'));

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

    public function test_non_admin_user_cannot_create_users()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('users.store'), [
                'name' => 'Test User',
                'email' => 'test@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => Role::OPERATOR->value,
            ]);

        $response->assertForbidden()
            ->assertSee('Você não tem permissão para criar usuários.');
    }

    public function test_non_admin_user_cannot_update_users()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch(route('users.update', $user), [
                'role' => Role::ADMIN->value,
            ]);

        $response->assertForbidden()
            ->assertSee('Você não tem permissão para atualizar usuários.');
    }

    public function test_non_admin_user_cannot_destroy_users()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('users.destroy', $user));

        $response->assertForbidden()
            ->assertSee('Você não tem permissão para deletar usuários.');
    }

    public function test_can_list_users()
    {
        $user = User::factory()->admin()->create();
        User::factory()->count(3)->create();

        $this->actingAs($user)
            ->get(route('users'))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('users', 3);
                $page->has('pagination', function (Assert $pagination) {
                    $pagination->where('perPage', 15)
                        ->where('total', 3)
                        ->where('currentPage', 1)
                        ->where('lastPage', 1);
                });
            });
    }

    public function test_can_search_users_by_name_or_email()
    {
        $user = User::factory()->admin()->create();

        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Doe']);
        User::factory()->create(['email' => 'john@example.com']);
        User::factory()->create(['email' => 'jane@example.com']);

        $this->actingAs($user)
            ->get(route('users', ['searchTerm' => 'John']))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('users', 2)
                    ->has('users.0', function (Assert $user) {
                        $user->where('name', 'John Doe')->etc();
                    })
                    ->has('users.1', function (Assert $user) {
                        $user->where('email', 'john@example.com')->etc();
                    });

                $page->has('filters', function (Assert $filters) {
                    $filters->where('searchTerm', 'John')->etc();
                });
            });
    }

    public function test_can_order_users_by_name()
    {
        $user = User::factory()->admin()->create();

        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Doe']);

        $this->actingAs($user)
            ->get(route('users', ['sortBy' => 'name', 'sortDirection' => 'asc']))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('users', 2)
                    ->has('users.0', function (Assert $user) {
                        $user->where('name', 'Jane Doe')->etc();
                    })
                    ->has('users.1', function (Assert $user) {
                        $user->where('name', 'John Doe')->etc();
                    });

                $page->has('filters', function (Assert $filters) {
                    $filters->where('sortBy', 'name')
                        ->where('sortDirection', 'asc')
                        ->etc();
                });
            });
    }

    public function test_can_order_users_by_email()
    {
        $user = User::factory()->admin()->create();

        User::factory()->create(['email' => 'john@example.com']);
        User::factory()->create(['email' => 'jane@example.com']);

        $this->actingAs($user)
            ->get(route('users', ['sortBy' => 'email', 'sortDirection' => 'asc']))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('users', 2)
                    ->has('users.0', function (Assert $user) {
                        $user->where('email', 'jane@example.com')->etc();
                    })
                    ->has('users.1', function (Assert $user) {
                        $user->where('email', 'john@example.com')->etc();
                    });

                $page->has('filters', function (Assert $filters) {
                    $filters->where('sortBy', 'email')
                        ->where('sortDirection', 'asc')
                        ->etc();
                });
            });
    }

    public function test_can_order_users_by_role()
    {
        $user = User::factory()->admin()->create();

        User::factory()->operator()->create();
        User::factory()->seller()->create();

        $this->actingAs($user)
            ->get(route('users', ['sortBy' => 'role', 'sortDirection' => 'asc']))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('users', 2)
                    ->has('users.0', function (Assert $user) {
                        $user->where('role', Role::OPERATOR->value)->etc();
                    })
                    ->has('users.1', function (Assert $user) {
                        $user->where('role', Role::SELLER->value)->etc();
                    });

                $page->has('filters', function (Assert $filters) {
                    $filters->where('sortBy', 'role')
                        ->where('sortDirection', 'asc')
                        ->etc();
                });
            });
    }

    public function test_can_order_users_in_descending_order()
    {
        $user = User::factory()->admin()->create();

        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Doe']);

        $this->actingAs($user)
            ->get(route('users', ['sortBy' => 'name', 'sortDirection' => 'desc']))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('users', 2)
                    ->has('users.0', function (Assert $user) {
                        $user->where('name', 'John Doe')->etc();
                    })
                    ->has('users.1', function (Assert $user) {
                        $user->where('name', 'Jane Doe')->etc();
                    });

                $page->has('filters', function (Assert $filters) {
                    $filters->where('sortBy', 'name')
                        ->where('sortDirection', 'desc')
                        ->etc();
                });
            });
    }

    public function test_can_filter_users_by_role()
    {
        $user = User::factory()->admin()->create();

        User::factory()->operator()->create();
        User::factory()->seller()->create();

        $this->actingAs($user)
            ->get(route('users', ['role' => Role::OPERATOR->value]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('users', 1)
                    ->has('users.0', function (Assert $user) {
                        $user->where('role', Role::OPERATOR->value)->etc();
                    });

                $page->has('filters', function (Assert $filters) {
                    $filters->where('role', Role::OPERATOR->value)->etc();
                });
            });
    }

    public function test_can_paginate_users()
    {
        $user = User::factory()->admin()->create();

        User::factory()->count(20)->create();

        $this->actingAs($user)
            ->get(route('users', ['page' => 2]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('users', 5);
                $page->has('pagination', function (Assert $pagination) {
                    $pagination->where('perPage', 15)
                        ->where('total', 20)
                        ->where('currentPage', 2)
                        ->where('lastPage', 2);
                });
            });
    }

    public function test_can_create_user()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)
            ->post(route('users.store'), [
                'name' => 'Test User',
                'email' => 'test@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => Role::OPERATOR->value,
            ]);

        $response->assertRedirect()
            ->assertSessionHas('message', 'O usuário foi criado com sucesso.');
    }

    public function test_cannot_create_user_with_invalid_data()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)
            ->post(route('users.store'), [
                'name' => '',
                'email' => 'invalid-email',
                'password' => 'password',
                'password_confirmation' => 'different-password',
                'role' => 'invalid-role',
            ]);

        $response->assertSessionHasErrors([
            'name' => 'O campo nome é obrigatório.',
            'email' => 'O campo email deve ser um endereço de e-mail válido.',
            'role' => 'O papel selecionado é inválido.',
            'password' => 'A confirmação do campo senha não confere.',
        ]);
    }

    public function test_can_update_user()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->patch(route('users.update', $user), [
                'name' => 'Updated Name',
                'email' => $user->email,
                'role' => $user->role->value,
            ]);

        $response->assertRedirect()
            ->assertSessionHas('message', 'O usuário foi atualizado com sucesso.');
    }

    public function test_cannot_update_user_with_invalid_data()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->patch(route('users.update', $user));

        $response->assertSessionHasErrors([
            'name' => 'O campo nome é obrigatório.',
            'email' => 'O campo email é obrigatório.',
            'role' => 'O campo papel é obrigatório.',
        ]);
    }

    public function test_can_delete_user()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->delete(route('users.destroy', $user));

        $response->assertRedirect()
            ->assertSessionHas('message', 'O usuário foi deletado com sucesso.');
    }

    public function test_cannot_delete_user_with_invalid_id()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->delete(route('users.destroy', 999));

        $response->assertNotFound();
    }
}
