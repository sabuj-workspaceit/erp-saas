<?php

namespace App\Http\Controllers\Main;


use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class RegisterController extends Controller
{
    public function show()
    {
        return view('main.register');
    }


    public function store(Request $r)
    {
        $data = $r->validate([
            'company_name' => ['required', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);


        $sub = Str::slug(Str::limit($data['company_name'], 30, ''));
        $sub = preg_replace('/[^a-z0-9-]/', '', $sub);
        $sub = $sub ?: 'tenant' . now()->timestamp;


        $tenant = Tenant::create(['name' => $data['company_name'], 'subdomain' => $sub]);


        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'tenant_id' => $tenant->id,
            'system_role' => 'company_admin',
        ]);


        // Optional: login user on main then redirect to tenant login or SSO
        return redirect()->away('https://' . $tenant->subdomain . '.' . config('app-domain.base') . '/login');
    }
}
