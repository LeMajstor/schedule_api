<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactType;

class ContactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $contacts = [
        'whatsapp',
        'instagram',
        'facebook',
        'linkedin',
        'youtube',
        'twitter',
        'email',
        'phone'
    ];

    public function run()
    {

        // insert contacts registers
        foreach ($this->contacts as $contact) 
        {
            $entity = new ContactType();
            $entity->type = $contact;
            $entity->save();
        }
    }
}
