<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ], [
                // Kustomisasi pesan error (opsional)
                'username.required' => 'Username tidak boleh kosong.',
                'password.required' => 'Password tidak boleh kosong.',
            ]);
            
            $username = $request->input('username');
            $password = $request->input('password');

            // Data user hardcoded (password: 123 dalam md5)
            $dataUser = User::where('username', $username)->first();

            if ($dataUser) {
                if (password_verify($password, $dataUser['password'])) {
                    $request->session()->put([
                        'username'   => $dataUser['username'],
                        'role'       => $dataUser['role'],
                        'isLoggedIn' => true,
                    ]);

                    return redirect('/');
                } else {
                    return redirect()->back()
                        ->with('failed', 'Username & Password Salah');
                }
            } else {
                return redirect()->back()
                    ->with('failed', 'Username Tidak Ditemukan');
            }
        }

        return view('v_login');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('login');
    }
}