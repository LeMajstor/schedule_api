<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{

    // Table name
    protected $table = 'contacts_type';

    // Table timestamps
    public $timestamps = false;

    // ContactType relationship
    public function contact() 
    {
        return $this->hasMany('App\Models\Contact');
    }

}
