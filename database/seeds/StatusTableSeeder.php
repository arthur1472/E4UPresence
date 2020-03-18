<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['id' => 1, 'name' => 'Afwezig', 'display_name' => 'Afwezig'],
            ['id' => 2, 'name' => 'Aanwezig', 'display_name' => 'Aanwezig'],
        ];

        foreach ($statuses as $status) {
            \App\Status::create($status)->save();
        }
    }
}
