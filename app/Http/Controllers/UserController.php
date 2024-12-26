<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

public function register(Request $request)
{
    // Validasi di server
    $validator = Validator::make($request->all(), [
        'nik' => 'required',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required',
        'password' => 'required|min:8|regex:/[A-Z]/', // Password harus minimal 8 karakter dan setidaknya 1 huruf besar
        'password_confirmation' => 'required|same:password',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Lanjutkan dengan penyimpanan user
}

public function checkEmail(Request $request)
{
    $email = $request->input('email');
    
    // Cek apakah email sudah ada di database
    $exists = User::where('email', $email)->exists();

    // Mengembalikan response JSON
    return response()->json(['exists' => $exists]);
}
}


