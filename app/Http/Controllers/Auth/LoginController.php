<?php
// CONTROLLER: app/Http/Controllers/Auth/LoginController.php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function create()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }
    


public function store(Request $request)
{
$credentials = $request->validate([
'email' => ['required', 'email'],
'password' => ['required', 'string'],
]);


if (Auth::attempt($credentials, $request->boolean('remember'))) {
$request->session()->regenerate();
return redirect()->intended('dashboard');
}


return back()->withErrors(['email' => 'Credenciais inválidas.'])->onlyInput('email');
}


public function destroy(Request $request)
{
Auth::logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
return redirect()->route('login');
}
}


?>