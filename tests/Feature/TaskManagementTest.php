<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_lists_tasks_and_filters_by_status(): void
    {
        $pendingTask = Task::factory()->create([
            'title' => 'Prepare the report',
            'status' => 'pending',
        ]);
        $completedTask = Task::factory()->create([
            'title' => 'Send the invoice',
            'status' => 'completed',
        ]);

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertSee($pendingTask->title)
            ->assertSee($completedTask->title);

        $this->get(route('tasks.index', ['status' => 'pending']))
            ->assertOk()
            ->assertSee($pendingTask->title)
            ->assertDontSee($completedTask->title);

    }

    public function test_task_can_be_created_with_valid_data(): void
    {
        $this->post(route('tasks.store'), [
            'title' => 'Book a dentist appointment',
            'description' => 'Call after lunch.',
            'due_date' => '2026-10-02',
        ])->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'title' => 'Book a dentist appointment',
            'description' => 'Call after lunch.',
            'due_date' => '2026-10-02 00:00:00',
            'status' => 'pending',
        ]);
    }

    public function test_task_creation_rejects_invalid_data(): void
    {
        $this->from(route('tasks.index'))
            ->post(route('tasks.store'), [
                'title' => '',
                'due_date' => 'not-a-date',
            ])
            ->assertSessionHasErrors(['title', 'due_date']);

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_task_can_be_updated(): void
    {
        $task = Task::factory()->create();

        $this->put(route('tasks.update', $task), [
            'title' => 'Updated task title',
            'description' => 'Updated details.',
            'due_date' => '2026-10-05',
            'status' => 'completed',
        ])->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated task title',
            'description' => 'Updated details.',
            'due_date' => '2026-10-05 00:00:00',
            'status' => 'completed',
        ]);
    }

    public function test_task_status_can_be_toggled(): void
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $this->patch(route('tasks.status', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'completed']);

        $this->patch(route('tasks.status', $task));

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'pending']);
    }

    public function test_task_can_be_deleted(): void
    {
        $task = Task::factory()->create();

        $this->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_task_text_is_escaped_on_the_dashboard(): void
    {
        $task = Task::factory()->create([
            'title' => '<script>alert("task")</script>',
            'description' => '<img src=x onerror=alert(1)>',
        ]);

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertSee($task->title)
            ->assertSee($task->description)
            ->assertDontSee($task->title, false)
            ->assertDontSee($task->description, false);

    }
}
