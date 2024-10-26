<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Kategori;

class KategoriController extends Controller
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
        return view('kategori', [
            'title' => 'Kategori - Yuk Kulineran',
            'page' => "Kategori",
        ]);
    }

    // handle fetch all kategoris ajax request
    public function fetchAll()
    {
        $i = 1;
        $kategoris = Kategori::get();
        $output = '';
        if ($kategoris->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle" id="datatable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Jenis Wisata Kuliner</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($kategoris as $kategori) {
                $output .= '<tr>
                <td>' . $i++ . '</td>
                <td>' . $kategori->jenis_wisata_kuliner . '</td>
                <td>
                  <a href="#" id="' . $kategori->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi-pencil-square h4"></i></a>

                  <a href="#" id="' . $kategori->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }

    // handle insert a new kategori ajax request
    public function store(Request $request)
    {
        $kategoriData = ['jenis_wisata_kuliner' => $request->jenis_wisata_kuliner];
        Kategori::create($kategoriData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // handle edit an kategori ajax request
    public function edit(Request $request)
    {
        $id = $request->id;
        $kategori = Kategori::find($id);
        return response()->json($kategori);
    }

    // handle update an employee ajax request
    public function update(Request $request)
    {
        $kategori = Kategori::find($request->id);

        $kategoriData = ['jenis_wisata_kuliner' => $request->jenis_wisata_kuliner];

        $kategori->update($kategoriData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // handle delete an portfolio ajax request
    public function delete(Request $request)
    {
        $id = $request->id;
        $kategori = Kategori::find($id);
        if ($kategori) {
            Kategori::destroy($id);
        }
    }
}
