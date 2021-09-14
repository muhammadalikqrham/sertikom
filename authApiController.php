<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Hash;
use DB;

class authApiController extends Controller
{
    public function register(Request $request)
    {
        $attr = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|min:6|confirmed',
        ]);
        $input = $request->except('password');
        $input['password']=bcrypt($request->password);

        $user = User::create($input);
        
        return $user;
    }

    public function login(Request $request)
    {   
        $attr = $request->validate([
            'email'=>'required|string',
            'password'=>'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return 'Authentikasi Gagal';
        }

        $user = auth()->user();
        $user['token'] = auth()->user()->createToken('API Token')->plainTextToken;

        return $user;
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return 'Logout Berhasil';
    }
    public function authenticatedUser()
    {
        return auth()->user();
    }
}
