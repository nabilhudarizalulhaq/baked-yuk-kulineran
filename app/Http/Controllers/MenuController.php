<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Menu;
use App\Models\WisataKuliner;

class MenuController extends Controller
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
        if (auth()->user()->role == "admin") {
            $wisata_kuliners = WisataKuliner::all();
        } else {
            $wisata_kuliners = WisataKuliner::where('id_user', auth()->user()->id)->first();
        }
        return view('menu', [
            'title' => 'Menu - Yuk Kulineran',
            'page' => "Menu",
            'wisata_kuliners' => $wisata_kuliners,
        ]);
    }

    // handle fetch all menus ajax request
    public function fetchAll()
    {
        $i = 1;
        if (auth()->user()->role == "admin") {
            $menus = Menu::join('wisata_kuliners', 'menus.id_wisata_kuliner', '=', 'wisata_kuliners.id')
                ->join('users', 'wisata_kuliners.id_user', '=', 'users.id')
                ->select('menus.*', 'wisata_kuliners.nama_wisata_kuliner')
                ->get();
        } else {
            $menus = Menu::join('wisata_kuliners', 'menus.id_wisata_kuliner', '=', 'wisata_kuliners.id')
                ->join('users', 'wisata_kuliners.id_user', '=', 'users.id')
                ->select('menus.*', 'wisata_kuliners.nama_wisata_kuliner')
                ->where('wisata_kuliners.id_user', auth()->user()->id)
                ->get();
        }
        $output = '';
        if ($menus->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle" id="datatable">
            <thead>
              <tr>
                <th>No.</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Wisata Kuliner</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($menus as $menu) {
                $output .= '<tr>
                <td>' . $i++ . '</td>
                <td><img src="storage/menu/' . $menu->foto_kuliner . '" width="300px" class="img-fluid img-thumbnail"></td>
                <td>' . $menu->nama_kuliner . '</td>
                <td>' . $menu->harga . '</td>
                <td>' . $menu->deskripsi . '</td>
                <td>' . $menu->nama_wisata_kuliner . '</td>
                <td>
                  <a href="#" id="' . $menu->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi-pencil-square h4"></i></a>

                  <a href="#" id="' . $menu->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }

    // handle insert a new menu ajax request
    public function store(Request $request)
    {
        $file = $request->file('foto_kuliner');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/menu', $fileName);

        $menuData = ['foto_kuliner' => $fileName, 'nama_kuliner' => $request->nama_kuliner, 'harga' => $request->harga, 'deskripsi' => $request->deskripsi, 'id_wisata_kuliner' => $request->id_wisata_kuliner];
        Menu::create($menuData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // handle edit an menu ajax request
    public function edit(Request $request)
    {
        $id = $request->id;
        $menu = Menu::find($id);
        return response()->json($menu);
    }

    // handle update an employee ajax request
    public function update(Request $request)
    {
        $fileName = '';
        $menu = Menu::find($request->id);
        if ($request->hasFile('foto_kuliner')) {
            $file = $request->file('foto_kuliner');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/menu', $fileName);
            if ($menu->foto_kuliner) {
                Storage::delete('public/menu/' . $menu->foto_kuliner);
            }
        } else {
            $fileName = $request->old_foto_kuliner;
        }

        $menuData = ['foto_kuliner' => $fileName, 'nama_kuliner' => $request->nama_kuliner, 'harga' => $request->harga, 'deskripsi' => $request->deskripsi, 'id_wisata_kuliner' => $request->id_wisata_kuliner];

        $menu->update($menuData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // handle delete an portfolio ajax request
    public function delete(Request $request)
    {
        $id = $request->id;
        $menu = Menu::find($id);
        if (Storage::delete('public/menu/' . $menu->foto_kuliner)) {
            Menu::destroy($id);
        }
    }
}
