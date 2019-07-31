<?php

namespace App\Http\Controllers\Auth;

use DateTime;
use App\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255', 'min:4', 'unique:employees'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'employeeName' => ['required', 'string', 'max:50', 'unique:employees'],
            'position' => ['required', 'string', 'max:30',],
            'idNumber' => ['required', 'string', 'numeric', 'unique:employees'],
            'licenseNumber' => ['required', 'string', 'max:50', 'unique:employees'],
            'userType' => ['required', 'string', 'max:20',],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return Employee::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'employeeName' => $data['employeeName'],
            'position' => $data['position'],
            'idNumber' => $data['idNumber'],
            'licenseNumber' => $data['licenseNumber'],
            'userType' => $data['userType'],
            'managedBy' => (Auth::check() == true) ? Auth::user()->username : NULL,
            'managedDate' => new DateTime(),
        ]);
    }
}
