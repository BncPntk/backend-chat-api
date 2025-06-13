<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function add(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        if ($user->id == $id) {
            return response()->json(['message' => 'Saját felhasználó nem jelölheő meg.'], 400);
        }

        // csak megerősített felhasználók jelölhetőek
        $target = User::where('id', $id)->whereNotNull('email_verified_at')->first();
        if (!$target) {
            return response()->json(['message' => 'Nem található megerősített felhasználó.'], 404);
        }

        if ($user->friends()->where('friend_id', $id)->exists()) {
            return response()->json(['message' => 'Már korábban hozzáadva.'], 409);
        }

        $user->friends()->attach($id);

        // Elfogadás vagy jelölés
        $mutual = $target->friends()->where('friend_id', $user->id)->exists();
        return response()->json([
            'message' => $mutual ? 'Barát kérelem elfogadva.' : 'Barátnak jelölés elküldve.',
            'mutual' => $mutual
        ]);
    }

    //barátlista lekérdezése
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $mutuals = $user->friends()->whereHas('friends', function ($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        return response()->json($mutuals);
    }
}
