<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Menu;
use App\Models\MenuTransaksi;
use App\Models\WisataKuliner;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'admin') {
            $wisataKuliners = WisataKuliner::all();
            $menus = Menu::all();
        } else {
            $wisataKuliners = WisataKuliner::where('id_user', $user->id)->get();

            // Fetch all menus for each wisata kuliner
            foreach ($wisataKuliners as $wisataKuliner) {
                $menus[$wisataKuliner->id] = Menu::where('id_wisata_kuliner', $wisataKuliner->id)->get();
            }
        }

        return view('transaksi', [
            'title' => 'Transaksi - Yuk Kulineran',
            'page' => 'Transaksi',
            'menus' => $menus,
            'wisataKuliners' => $wisataKuliners
        ]);
    }

    public function fetchAll()
    {
        $i = 1;
        $transaksis = Transaksi::get();
        if (auth()->user()->role == "admin") {
            $transaksis = Transaksi::get();
        } else {
            $transaksis = Transaksi::join('wisata_kuliners', 'transaksis.id_wisata_kuliner', '=', 'wisata_kuliners.id')
                ->select('transaksis.*')
                ->where('wisata_kuliners.id_user', auth()->user()->id)
                ->get();
        }
        $output = '';
        if ($transaksis->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle" id="datatable">
            <thead>
              <tr>
                <th>No.</th>
                <th>No Referensi Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Total Harga</th>
                <th>Status Transaksi</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
            foreach ($transaksis as $transaksi) {
                $output .= '<tr>
                <td>' . $i++ . '</td>
                <td>' . $transaksi->no_referensi_transaksi . '</td>
                <td>' . $transaksi->tgl_transaksi . '</td>
                <td>' . $transaksi->total_harga . '</td>
                <td>' . ($transaksi->status_transaksi == '0' ? 'In Progress' : ($transaksi->status_transaksi == '1' ? 'Completed' : 'Cancelled')) . '</td>
                <td>
                  <a href="#" id="' . $transaksi->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi-pencil-square h4"></i></a>
                  <a href="#" id="' . $transaksi->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }

    public function fetchMenus(Request $request)
    {
        $menus = Menu::where('id_wisata_kuliner', $request->id_wisata_kuliner)->get();
        return response()->json($menus);
    }

    public function store(Request $request)
    {
        $tgl_transaksi = Carbon::now()->format('Y-m-d');
        $no_referensi_transaksi = '00' . Carbon::now()->format('Ymd') . str_pad(Transaksi::count() + 1, 4, '0', STR_PAD_LEFT);

        // Calculate total price
        $total_harga = 0;
        foreach ($request->menus as $index => $menu_id) {
            $menu = Menu::find($menu_id);
            $amount = $request->amounts[$index];
            $total_harga += $menu->harga * $amount;
        }

        $transaksiData = [
            'no_referensi_transaksi' => $no_referensi_transaksi,
            'tgl_transaksi' => $tgl_transaksi,
            'total_harga' => $total_harga, // Set total harga
            'id_wisata_kuliner' => $request->id_wisata_kuliner,
            'id_user' => Auth::id()
        ];

        $transaksi = Transaksi::create($transaksiData);

        // Save menu transactions
        foreach ($request->menus as $index => $menu_id) {
            $amount = $request->amounts[$index]; // Get the amount for the menu
            MenuTransaksi::create([
                'id_menu' => $menu_id,
                'id_transaksi' => $transaksi->id,
                'amount' => $amount // Save the amount
            ]);
        }

        return response()->json([
            'status' => 200,
        ]);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $transaksi = Transaksi::find($id);
        return response()->json($transaksi);
    }

    public function update(Request $request)
    {
        $transaksi = Transaksi::find($request->id);

        // Only update status_transaksi
        $transaksi->status_transaksi = $request->status_transaksi;
        $transaksi->save();

        return response()->json([
            'status' => 200,
        ]);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $transaksi = Transaksi::find($id);
        if ($transaksi) {
            Transaksi::destroy($id);
        }
    }
}
