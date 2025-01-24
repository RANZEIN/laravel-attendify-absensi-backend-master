<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Ranzein',
            'email' => 'ranzdhika@gmail.com',
            'password' => Hash::make('@WHF487pp31'),
        ]);

        // data dummy for company
        \App\Models\Company::create(
            [
                'name' => 'PT.Mencari Cinta',
                'email' => 'ranzdhika@gmail.com',
                'address' => 'Jl.Manisi atas Gg. Reformasi No.006',
                'latitude' => '37.7749',
                'longitude' => '-122.4194',
                'radius_km' => '0.5',
                'time_in' => '08:00',
                'time_out' => '17:00',
            ]);

        $this->call([
            AttendanceSeeder::class,
            PermissionSeeder::class,
            // Your other seeders here...
        ]);


    }
}
