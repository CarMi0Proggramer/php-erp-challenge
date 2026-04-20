<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $paginator = User::filter($request->query())->paginate(15);

        return Inertia::render('Users', [
            'users' => $paginator->items(),
            'pagination' => [
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
            ],
            'filters' => $request->only([
                'searchTerm',
                'sortBy',
                'sortDirection',
                'role',
            ]),
        ]);
    }

    #[Authorize('create', User::class)]
    public function store(UserStoreRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return back()->with('message', 'O usuário foi criado com sucesso.');
    }

    #[Authorize('update', User::class)]
    public function update(
        UserUpdateRequest $request,
        User $user
    ): RedirectResponse {
        $user->update($request->validated());

        return back()->with('message', 'O usuário foi atualizado com sucesso.');
    }

    #[Authorize('destroy', User::class)]
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('message', 'O usuário foi deletado com sucesso.');
    }
}
