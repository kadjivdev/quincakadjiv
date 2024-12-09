<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Administrateur Kadjiv',
            'email' => 'admin@gmail.com',
            'phone' => '229 96261010',
            'address' => 'Bénin COtonou',
            'is_active' => true,
            'password' => bcrypt('password'),
            'point_vente_id' => 1
        ]);

        $user->assignRole('Super Admin');
        $user->save();
    }
}
