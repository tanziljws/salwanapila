<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * CREDENTIAL USER:
     * Email: user@test.com
     * Password: user123
     * 
     * Email: salwa@smkn4.com
     * Password: salwa123
     */
    public function run(): void
    {
        // Create test user 1
        $user1 = User::firstOrNew(['email' => 'user@test.com']);
        if (!$user1->exists) {
            $user1->fill([
                'name' => 'Test User',
                'email' => 'user@test.com',
                'password' => Hash::make('user123'),
                'nisn' => '1234567890',
            ])->save();
            $this->command->info('User 1 created: user@test.com / user123');
        } else {
            $user1->update(['password' => Hash::make('user123')]);
            $this->command->info('User 1 password updated: user@test.com / user123');
        }

        // Create test user 2
        $user2 = User::firstOrNew(['email' => 'salwa@smkn4.com']);
        if (!$user2->exists) {
            $user2->fill([
                'name' => 'Salwa Napila',
                'email' => 'salwa@smkn4.com',
                'password' => Hash::make('salwa123'),
                'nisn' => '0987654321',
            ])->save();
            $this->command->info('User 2 created: salwa@smkn4.com / salwa123');
        } else {
            $user2->update(['password' => Hash::make('salwa123')]);
            $this->command->info('User 2 password updated: salwa@smkn4.com / salwa123');
        }
    }
}
