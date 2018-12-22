<?php

use Illuminate\Database\Seeder;

class DmsCreditsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::ability()->create([
            'name' => 'create_codes',
            'title' => 'Create new codes for credits.'
        ]);

        Bouncer::ability()->create([
            'name' => 'role_credits',
            'title' => 'Roles with this ability can have credits.'
        ]);
    }
}
