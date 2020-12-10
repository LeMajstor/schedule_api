<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailValidation;
use App\Mail\SendPassChangeEmail;

class UsersController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    // Create user
    public function create (CreateUserRequest $request) 
    {
        $form = $request->validated();
        $form['password'] = password_hash($form['password'], PASSWORD_DEFAULT);
        
        // Generate token and send email
        $email = $this->sendValidationEmail($form['email']);

        if ($email['error'] == 0)
        {
            $form['verification_token'] = $email['token'];
            if (User::create($form)) 
            {
                // User registered
                return response('Register stored successfully.', 200);
            } else 
            {
                // User not registered
                return response('Register couldn\'t be stored.', 500);
            }
        } else
        {
            // Email couldn't be sent
            return response('Email couldn\'t be sent.', 500);
        }
       
    }

    // Update user
    public function update (UpdateUserRequest $request) 
    {
        $form = $request->validated();
        $id = $request->route('id');

        // Search user in database
        $user = User::find($id);

        if (isset($user) && ! is_null($user)) {

            if (isset($form['email']) && ! is_null($form['email'])) {
                // Email input is set and is not null
                $email = $this->sendValidationEmail($form['email']);
                
                if ($email['error'] == 0) {
                    // Email has been sent
                    $form['verification_token'] = $email['token'];
                    $form['email_verified_at'] = null;
                } else {
                    // Email couldn't be sent
                    return response('Email couldn\'t be sent.', 500);
                }
            }
    
            if (isset($form['password']) && ! is_null($form['password'])) {
                // Password input is set and is not null
                $email = $this->sendPassChangeEmail($user->name, $user->email);
                
                if ($email['error'] == 0) {
                    // Email has been sent
                    $form['password'] = password_hash($form['password'], PASSWORD_DEFAULT);
                } else {
                    // Email couldn't be sent
                    return response('Email couldn\'t be sent.', 500);
                }
            }

            $user->update($form);
            return response('User updated successfully', 200);

        } else {
            // User was not found
            return response('User \'id\' was not found.', 500);
        }

    }

    // Validate account
    public function emailValidate (Request $request)
    {
        $token = $request->query('token');
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
    
    // Send email confirmation with token
    public function sendValidationEmail($email) 
    {
        $token = md5(uniqid(rand(), true));
        $link = route('email.validate', ['token' => $token]);

        $message = new SendEmailValidation($token, $link);
        Mail::to($email)->send($message);
        
        return Mail::failures() 
         ? ['error' => 1] // If email has not been sent
         : ['error' => 0, 'token' => $token]; // If email has been sent          
    }

    // Send email confirmation with token
    public function sendPassChangeEmail($name, $email) 
    {
        $message = new SendPassChangeEmail($name);
        Mail::to($email)->send($message);
        
        return Mail::failures() 
         ? ['error' => 1] // If email has not been sent
         : ['error' => 0]; // If email has been sent          
    }

}
