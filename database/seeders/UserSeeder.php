<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($this->command->confirm(" Do you want to refresh the database ?")) {
            $this->command->call('migrate:fresh');
            $this->command->info(" Database was refreshed");
        }

        $nbOfUsers = (int) $this->command->ask("How many of user you want generate ?", 10);
        User::factory($nbOfUsers)->create();
    }
}
