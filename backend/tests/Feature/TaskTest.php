<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class TaskTest extends TestCase
{
    /** @test */
    public function it_creates_a_task()
    {
        $user = User::first() ?? User::factory()->create(); // para evitar error si no hay usuarios

        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Tarea persistente',
            'description' => 'Esta no se borra',
            'status' => 'pendiente',
            'due_date' => now()->addDays(3),
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Tarea persistente',
            'user_id' => $user->id,
        ]);
    }
    /** @test */
    public function can_view_a_task()
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'View task',
            'description' => 'Description',
            'status' => 'pendiente',
            'due_date' => now()->addDays(1)
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'title' => 'View task',
                'user_id' => $user->id,
            ]);
    }

    /** @test */
    public function can_update_a_task()
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Original task',
            'description' => 'Description',
            'status' => 'pendiente',
            'due_date' => now()->addDays(1)
        ]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated task',
            'status' => 'en_proceso',
        ]);

        $response->assertOk()
            ->assertJsonFragment([
                'title' => 'Updated task',
                'status' => 'en_proceso',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated task',
            'status' => 'en_proceso'
        ]);
    }

    /** @test */
    public function can_delete_a_task()
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Task to delete',
            'description' => 'Temporary',
            'status' => 'pendiente',
            'due_date' => now()->addDays(1)
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }
}
