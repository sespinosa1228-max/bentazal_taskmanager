<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::factory()->create([
            'title' => 'Review the week ahead',
            'description' => 'Set the priorities before the week gets moving.',
            'due_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
        ]);

        Task::factory()->create([
            'title' => 'Clear the open loops',
            'description' => 'Follow up on the notes and messages still waiting for a reply.',
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
        ]);

        Task::factory()->create([
            'title' => 'Set up the task board',
            'description' => 'The essentials are ready. Add a task whenever something needs your attention.',
            'status' => 'completed',
        ]);
    }
}
