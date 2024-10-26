<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class PenggunaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pengguna', [
            'title' => 'Pengguna - Yuk Kulineran',
            'page' => "Pengguna",
        ]);
    }

    // handle fetch all penggunas ajax request
    public function fetchAll()
    {
        $i = 1;
        $penggunas = User::all();
        $output = '';
        if ($penggunas->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle" id="datatable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Email</th>
                <th>No. HP</th>
                <th>Role</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($penggunas as $pengguna) {
                $output .= '<tr>
                <td>' . $i++ . '</td>
                <td>' . $pengguna->full_name . '</td>
                <td>' . $pengguna->name . '</td>
                <td>' . $pengguna->email . '</td>
                <td>' . $pengguna->phone_number . '</td>
                <td>' . $pengguna->role . '</td>
                <td>
                  <a href="#" id="' . $pengguna->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi-pencil-square h4"></i></a>

                  <a href="#" id="' . $pengguna->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }

    // handle insert a new pengguna ajax request
    // public function store(Request $request)
    // {
    //     $file = $request->file('foto_kuliner');
    //     $fileName = time() . '.' . $file->getClientOriginalExtension();
    //     $file->storeAs('public/pengguna', $fileName);

    //     $penggunaData = ['foto_kuliner' => $fileName, 'nama_kuliner' => $request->nama_kuliner, 'harga' => $request->harga, 'deskripsi' => $request->deskripsi, 'id_wisata_kuliner' => $request->id_wisata_kuliner];
    //     Pengguna::create($penggunaData);
    //     return response()->json([
    //         'status' => 200,
    //     ]);
    // }

    // handle edit an pengguna ajax request
    public function edit(Request $request)
    {
        $id = $request->id;
        $pengguna = User::find($id);
        return response()->json($pengguna);
    }

    // handle update an employee ajax request
    public function update(Request $request)
    {
        $pengguna = User::find($request->id);
        $penggunaData = ['full_name' => $request->full_name, 'name' => $request->name, 'email' => $request->email, 'phone_number' => $request->phone_number];

        $pengguna->update($penggunaData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // handle delete an portfolio ajax request
    public function delete(Request $request)
    {
        $id = $request->id;
        $pengguna = User::find($id);
        if ($pengguna) {
            User::destroy($id);
        }
    }
}
