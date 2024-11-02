<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate 10 notes
        // Note::factory()->count(10)->create();

        $users = User::all();

        if ($users->count() == 0) {
            $this->command->info("please create some users !");
            return;
        }

        // Ask the user if they want to refresh (delete) the notes table
        if ($this->command->confirm('Do you want to refresh the notes table? This will delete all existing notes.')) {
            // Delete all records in the notes table
            Note::query()->forceDelete();

            $this->command->info('Notes table has been refreshed (all records deleted).');
        }

        $nbNotes = (int)$this->command->ask("How many of notes you want generate ?", 30);

        Note::factory($nbNotes)->make()->each(function ($note) use ($users) {
            $note->user_id = $users->random()->id;
            $note->save();
        });
    }
}
