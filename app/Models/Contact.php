<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    // Table name
    protected $table = 'contacts';

    // Table timestamps
    public $timestamps = false;

    // ContactType relationship
    public function contactType() 
    {
        return $this->belongsTo('App\Models\ContactType');
    }

}
