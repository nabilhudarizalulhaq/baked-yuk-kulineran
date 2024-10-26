<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendRegisterEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmailController extends Controller
{

    // api
    public function index($id)
    {
        // Mengambil pengguna dengan ID yang sesuai dan status 0
        $user = User::where('id', $id)
            ->where('status', '0')
            ->first();

        // Memeriksa apakah pengguna ditemukan
        if (!$user) {
            return response()->json([
                "message" => "User not found or status is not 0",
                "code" => 404
            ]);
        }

        // Mengirim email kepada pengguna
        Mail::to($user->email)->send(new SendRegisterEmail($user));

        return response()->json([
            "message" => "Email sent successfully",
            "code" => 200
        ]);
    }


    // web
    public function verification($id)
    {
        $id = base64_decode($id);
        return view('auth/verification', compact('id'));
        // return $id;
    }

    public function forgotPassword($id)
    {
        $id = base64_decode($id);
        return view('auth/newPassword', compact('id'));
        // return $id;
    }

    public function profile()
    {
        return view('profile');
    }

    public function activation(Request $request)
    {
        $user = User::find($request->id);

        if ($user->email_verified_at) {
            return redirect()->back()->with('status', 'Akun sudah diverifikasi sebelumnya!');
        }

        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();

        return redirect()->back()->with('status', 'Verifikasi akun berhasil, silahkan login di aplikasi!');
    }

    public function newPassword(Request $request)
    {
        $user = User::find($request->id);
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // alihkan ke vue js
        return redirect()->back()->with('status', 'Password baru akun berhasil, silahkan login di aplikasi!');
    }
}
