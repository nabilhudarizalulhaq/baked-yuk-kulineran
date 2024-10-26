<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendForgotPasswordEmail;
use App\Models\User;
use Illuminate\Http\Request;  // Use the correct Request class
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth/forgotPassword');
    }

    public function sendForgotPasswordEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            Mail::to($user->email)->send(new SendForgotPasswordEmail($user->id));
            return redirect()->back()->with('status', 'Berhasil mengirim email forgot password, silahkan cek email anda!');
        }

        return redirect()->back()->with('status', 'Email tidak ditemukan!');
    }
}
