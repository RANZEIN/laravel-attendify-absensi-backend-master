<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'ranzdhika',
            'email' => 'ranzdhika@gmail.com',
            'password' => Hash::make('@WHF487pp31'),
        ]);

        // data dummy for company
        \App\Models\Company::create([
            'name' => 'PT. Attendify',
            'email' => 'ranzdhika@gmail.com',
            'address' => 'Jl. Cibiru atas Gg. Reformasi No.6, Bandung, Jawabarat',
            'phone' => '082124847648',
            'latitude' => '-7.747033',
            'longitude' => '110.355398',
            'radius_km' => '0.5',
            'time_in' => '08:00',
            'time_out' => '17:00',
        ]);

        $this->call([
            AttendanceSeeder::class,
            PermissionSeeder::class,
            BroadcastSeeder::class,
        ]);
    }
}
