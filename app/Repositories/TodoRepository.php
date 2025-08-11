<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Models\User;
use App\Models\TodoItem;
use App\Events\TodoItemCreated;
use App\Interfaces\TodoRepositoryInterface;

class TodoRepository implements TodoRepositoryInterface
{
    public function create(array $data)
    {
        if (empty($data['title']) || empty($data['owner_id'])) {
            throw new \Exception("Title and owner_id are required");
        }
        return Todo::create($data);
    }

    public function find($id)
    {
        return Todo::with('items.addedBy', 'invitedUsers')->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $todo = Todo::findOrFail($id);
        $todo->update($data);
        return $todo;
    }

    public function delete($id)
    {
        Todo::destroy($id);
    }

    public function inviteUser($todoId, $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $todo = Todo::findOrFail($todoId);

        if (!$todo->invitedUsers->contains($user->id)) {
            $todo->invitedUsers()->attach($user->id);
        }

        return $user;
    }

    public function addItem($todoId, $content, $userId)
    {
        $todo = Todo::with('invitedUsers')->findOrFail($todoId);

        if (!$todo->invitedUsers->contains($userId) && $todo->owner_id !== $userId) {
            throw new \Exception("Not authorized to add items");
        }

        $item = $todo->items()->create([
            'content' => $content,
            'added_by' => $userId
        ]);

        broadcast(new TodoItemCreated($item))->toOthers();

        return $item;
    }
}
