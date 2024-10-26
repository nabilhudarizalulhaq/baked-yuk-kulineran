<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\WisataKuliner;
use App\Models\Kategori;
use App\Models\User;

class WisataKulinerController extends Controller
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
        $kategoris = Kategori::all();
        $users = User::all();
        return view('wisata_kuliner', [
            'title' => 'Wisata Kuliner - Yuk Kulineran',
            'page' => "Wisata Kuliner",
            'kategoris' => $kategoris,
            'users' => $users,
        ]);
    }

    // handle fetch all wisata_kuliners ajax request
    public function fetchAll()
    {
        $i = 1;
        if (auth()->user()->role == "admin") {
            $wisata_kuliners = WisataKuliner::join('users', 'wisata_kuliners.id_user', '=', 'users.id')
                ->select('wisata_kuliners.*', 'users.name')
                ->get();
        } else {
            $wisata_kuliners = WisataKuliner::join('users', 'wisata_kuliners.id_user', '=', 'users.id')
                ->select('wisata_kuliners.*', 'users.name')
                ->where('wisata_kuliners.id_user', auth()->user()->id)
                ->get();
        }
        $output = '';
        if ($wisata_kuliners->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle" id="datatable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Pemilik</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($wisata_kuliners as $wisata_kuliner) {
                $output .= '<tr>
                <td>' . $i++ . '</td>
                <td><img src="storage/wisata_kuliner/' . $wisata_kuliner->foto_wisata_kuliner . '" width="300px" class="img-fluid img-thumbnail"></td>
                <td>' . $wisata_kuliner->nama_wisata_kuliner . '</td>
                <td>' . $wisata_kuliner->alamat . '</td>
                <td>' . $wisata_kuliner->name . '</td>
                <td>
                  <a href="#" id="' . $wisata_kuliner->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi-pencil-square h4"></i></a>

                  <a href="#" id="' . $wisata_kuliner->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }

    // handle insert a new wisata_kuliner ajax request
    public function store(Request $request)
    {
        $file = $request->file('foto_wisata_kuliner');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/wisata_kuliner', $fileName);

        $wisataKulinerData = ['foto_wisata_kuliner' => $fileName, 'nama_wisata_kuliner' => $request->nama_wisata_kuliner, 'alamat' => $request->alamat, 'latitude' => $request->latitude, 'longitude' => $request->longitude, 'id_kategori' => $request->id_kategori, 'id_user' => $request->id_user];
        WisataKuliner::create($wisataKulinerData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // handle edit an wisata_kuliner ajax request
    public function edit(Request $request)
    {
        $id = $request->id;
        $wisata_kuliner = WisataKuliner::find($id);
        return response()->json($wisata_kuliner);
    }

    // handle update an employee ajax request
    public function update(Request $request)
    {
        $fileName = '';
        $wisata_kuliner = WisataKuliner::find($request->id);
        if ($request->hasFile('foto_wisata_kuliner')) {
            $file = $request->file('foto_wisata_kuliner');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/wisata_kuliner', $fileName);
            if ($wisata_kuliner->foto_wisata_kuliner) {
                Storage::delete('public/wisata_kuliner/' . $wisata_kuliner->foto_wisata_kuliner);
            }
        } else {
            $fileName = $request->old_foto_wisata_kuliner;
        }

        $wisataKulinerData = ['foto_wisata_kuliner' => $fileName, 'nama_wisata_kuliner' => $request->nama_wisata_kuliner, 'alamat' => $request->alamat, 'latitude' => $request->editLatitude, 'longitude' => $request->editLongitude, 'id_user' => $request->id_user];

        $wisata_kuliner->update($wisataKulinerData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // handle delete an portfolio ajax request
    public function delete(Request $request)
    {
        $id = $request->id;
        $wisata_kuliner = WisataKuliner::find($id);
        if (Storage::delete('public/wisata_kuliner/' . $wisata_kuliner->foto_wisata_kuliner)) {
            WisataKuliner::destroy($id);
        }
    }
}
