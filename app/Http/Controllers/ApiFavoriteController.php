<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiFavoriteController extends Controller
{
    //
    public function fetchFavorite(Request $request, $id_user)
    {
        try {
            // Fetch the favorites where id_user matches the given $id_user
            $favorites = DB::table('favorites')
            ->join('menus', 'favorites.id_menu', '=', 'menus.id')
            ->where('favorites.id_user', $id_user)
            ->select(
                'favorites.id as favorite_id',
                'favorites.id_user',
                'menus.id as menu_id',
                'menus.foto_kuliner',
                'menus.nama_kuliner',
                'menus.harga',
                'menus.deskripsi',
                'menus.id_wisata_kuliner'
            )
            ->get();

            // Check if any favorites were found
            if ($favorites->isEmpty()) {
                return response()->json([
                    'message' => 'No favorites found for this user',
                ], 404);
            }

            // Return the list of favorites
            return response()->json([
                'message' => 'Favorites retrieved successfully',
                'data' => $favorites
            ], 200);
        } catch (\Exception $e) {
            // Log the error message if needed for debugging
            // \Log::error("Error fetching favorites: " . $e->getMessage());

            // Return a JSON response with error message and status code 500
            return response()->json([
                'message' => 'An error occurred while fetching the favorites',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addFavorite(Request $request)
    {
        try {
            // Retrieve authenticated user's ID directly from the request
            $userId = $request->id_user; // Use $request->user()->id for authenticated user's ID
            $menuId = $request->id_menu;

            // Save the favorite to the database
            $favorite = Favorite::create([
                'id_user' => $userId,
                'id_menu' => $menuId
            ]);

            return response()->json([
                'message' => 'Favorite added successfully',
                'data' => $favorite
            ], 201);
        } catch (\Exception $e) {
            // Log the error message if needed for debugging
            // Log::error("Error adding favorite: " . $e->getMessage());

            // Return a JSON response with error message and status code 500
            return response()->json([
                'message' => 'An error occurred while adding the favorite',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
