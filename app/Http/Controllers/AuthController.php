<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Employee;
use App\Mail\SendWelcomeMailable;
use Exception;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Criação de funcionários
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
        try{
            $request->validate([
                'store_id' => 'required|exists:store,id',
                'name' => 'required|string',
                'lastname' => 'required|string',
                'email' => 'required|string|email|unique:employee',
                'cpf' => 'required|string|unique:employee',
                'rg' => 'required|string|unique:employee',
                'password' => 'required|string|confirmed'
            ]);
    
            $employee = new Employee([
                'store_id' => $request->store_id,
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'cpf' => $request->cpf,
                'rg' => $request->rg,
                'password' => bcrypt($request->password)
            ]);
    
            $employee->save();
    
            Mail::to($request->email)->send(new SendWelcomeMailable($request->name));
        } catch (Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Funcionário criado com sucesso!'
        ], 201);
    }

    /**
     * Login e criação de token
     * 
     * @param [string] email
     * @param [string] password
     * @param [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);

            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials)){
                return response()->json([
                    'message' => 'Não autorizado'
                ], 401);
            }
            $employee = $request->user();

            $tokenResult = $employee->createToken('Personal Access Token');
            $token = $tokenResult->token;

            if($request->remember_me)
            {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }

            $token->save();
        } catch (Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 501);
        }

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateString()
        ], 201);
    }

    /**
     * Logout e revogação de token
     * 
     * @return [string] message
     */
    public function logout(Request $request)
    {
        try{
            $request->user()->token()->revoke();
        } catch(Exception $e)
        {
            return response()->json([
                'message' => $e->getMessage()
            ], 501);
        }

        return response()->json([
            'message' => 'Você foi deslogado'
        ], 201);
    }

    /**
     * Pegar funcionário autenticado
     * 
     * @return [json] employee object
     */
    public function logged(Request $request)
    {
        return response()->json($request->user());
    }
}
