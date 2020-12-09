<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactsController extends Controller
{

    protected $model;

    public function __construct()
    {
        $this->model = new Contact();
    }

    public function create(CreateUserRequest $request) 
    {
        print_r($request);
    }

}
