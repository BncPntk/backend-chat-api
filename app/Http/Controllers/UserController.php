<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query()
            ->whereNotNull('email_verified_at')
            ->where('id', '!=', $request->user()->id);

        // szűrés név szerint
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // lapozás 2/oldal
        $users = $query->paginate(2);

        return response()->json($users);
    }
}
