<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Employee;

class AuthController extends Controller
{
    /**
     * Create Employee
     * 
     * @param [string] name
     * @param [string] lastname
     * @param [string] email
     * @param [string] cpf
     * @param [string] rg
     * @param [string] password
     * @param [string] password_confirmation
     * @param [string] remember_token
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|email|unique:employee',
            'cpf' => 'required|string|unique:employee',
            'rg' => 'required|string|unique:employee',
            'password' => 'required|string|confirmed'
        ]);

        $employee = new Employee([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'rg' => $request->rg,
            'password' => bcrypt($request->password)
        ]);

        $employee->save();

        return response()->json([
            'message' => 'Funcionário criado com sucesso!'
        ], 201);
    }
}
