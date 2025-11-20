<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * CREDENTIAL ADMIN:
     * Username: admin
     * Password: admin123
     */
    public function run(): void
    {
        // Check if admin already exists
        $existingAdmin = Admin::where('username', 'admin')->first();
        
        if (!$existingAdmin) {
            Admin::create([
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'email' => 'admin@smkn4.com',
                'full_name' => 'Administrator',
                'is_active' => true,
                'last_login' => now()
            ]);
            
            $this->command->info('Admin created successfully!');
            $this->command->info('Username: admin');
            $this->command->info('Password: admin123');
        } else {
            // Update password if admin exists
            $existingAdmin->update([
                'password' => Hash::make('admin123'),
                'is_active' => true,
            ]);
            
            $this->command->info('Admin password updated!');
            $this->command->info('Username: admin');
            $this->command->info('Password: admin123');
        }
    }
} 