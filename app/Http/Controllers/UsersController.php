<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailValidation;

class UsersController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    // Create user
    public function create(CreateUserRequest $request) 
    {
        // Validate request
        $form = $request->validated();
        
        // Hash password
        $form['password'] = password_hash($form['password'], PASSWORD_DEFAULT);
        
        // Generate token
        $form['verification_token'] = md5(uniqid(rand(), true));
        $token = $form['verification_token'];
        $confirmationLink = route('email.validate', ['token' => $token]);
        
        // Send email confirmation with token
        Mail::to($form['email'])
            ->send(new SendEmailValidation($token, $confirmationLink));
        
        if (User::create($form)) {
            // User registered
            return response('Register stored successfully.', 200);
        } else {
            // User not registered
            return response('Register couldn\'t be stored.', 500);
        }
    }

    // Validate account
    public function emailValidate(Request $request)
    {
        // Get token form query
        $token = $request->query('token');
        // Find user with token
        $user = User::where('verification_token', $token)->get()->first();

        if ($user) {
            // User with token is found
            $form['email_verified_at'] = date('Y-m-d H:i:s');
            $form['verification_token'] = "";
            $user->update($form);
            return response('User validated successfully', 200);
        } else {
            // User with token is couldn't be found
            return response('User couldn\'t be found', 500);
        }

    }

}
