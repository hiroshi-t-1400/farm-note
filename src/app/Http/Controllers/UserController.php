<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', User::class);

        $users = User::quey()
            ->defaultSort()
            ->paginate(15);

        return response()->view('users.index', compact('users'));
    }

    public function show(Request $request, User $user): Response
    {
        Gate::authorize('view', $user);

        $user->load('roles');

        return response()->view('users.show', compact('user'));
    }
}
