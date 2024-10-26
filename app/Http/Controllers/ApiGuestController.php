<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\WisataKuliner;
use Illuminate\Http\Request;

class ApiGuestController extends Controller
{
    //
    public function fetchWisataKuliner()
    {
        try {
            // Initialize a counter variable for numbering
            $i = 1;

            // Fetch data based on the user's role
            // if (auth()->user()->role == "admin") {
            //     $wisata_kuliners = WisataKuliner::join('users', 'wisata_kuliners.id_user', '=', 'users.id')
            //         ->select('wisata_kuliners.*', 'users.name')
            //         ->get();
            // } else {
            //     $wisata_kuliners = WisataKuliner::join('users', 'wisata_kuliners.id_user', '=', 'users.id')
            //         ->select('wisata_kuliners.*', 'users.name')
            //         ->where('wisata_kuliners.id_user', auth()->user()->id)
            //         ->get();
            // }

            $wisata_kuliners = WisataKuliner::join('users', 'wisata_kuliners.id_user', '=', 'users.id')
                ->select('wisata_kuliners.*', 'users.name')
                ->get();

            // Check if records are found
            if ($wisata_kuliners->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No record present in the database!',
                    'data' => []
                ], 200);
            }

            // Format the data for JSON response
            $data = [];
            foreach ($wisata_kuliners as $wisata_kuliner) {
                $data[] = [
                    'no' => $i++,
                    'foto_url' => url('storage/wisata_kuliner/' . $wisata_kuliner->foto_wisata_kuliner),
                    'nama' => $wisata_kuliner->nama_wisata_kuliner,
                    'alamat' => $wisata_kuliner->alamat,
                    'pemilik' => $wisata_kuliner->name
                ];
            }

            // Return a successful JSON response with data
            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that may occur
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching data.',
                'error' => $e
            ], 500);
        }
    }
    public function fetchKategori()
    {
        try {
            // Fetch all categories
            $kategoris = Kategori::all();

            // Check if there are any records
            if ($kategoris->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No record present in the database!',
                    'data' => []
                ], 200);
            }

            // Format the data as an array for JSON response
            $data = [];
            foreach ($kategoris as $index => $kategori) {
                $data[] = [
                    'no' => $index + 1,
                    'jenis_wisata_kuliner' => $kategori->jenis_wisata_kuliner,
                ];
            }

            // Return a JSON response with the data
            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            // Catch any errors and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
