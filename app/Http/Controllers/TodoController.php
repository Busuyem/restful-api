<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TodoRepository;
use App\Models\User;

class TodoController extends Controller
{
    protected $todoRepo;

    public function __construct(TodoRepository $todoRepo)
    {
        $this->todoRepo = $todoRepo;
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        try {
            $todo = $this->todoRepo->create([
                'title' => $request->title,
                'owner_id' => auth()->id()
            ]);

            return response()->json($todo, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $todo = $this->todoRepo->find($id);
            return response()->json($todo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        try {
            $todo = $this->todoRepo->update($id, [
                'title' => $request->title
            ]);
            return response()->json($todo);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed'], 500);
        }
    }

   
    public function destroy($id)
    {
        try {
            $this->todoRepo->delete($id);
            return response()->json(['message' => 'Todo deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Delete failed'], 500);
        }
    }

    public function invite(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|exists:users,username'
        ]);

        try {
            $user = $this->todoRepo->inviteUser($id, $request->username);
            return response()->json(['message' => 'User invited', 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
    public function addItem(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        try {
            $item = $this->todoRepo->addItem($id, $request->content, auth()->id());
            return response()->json($item, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }


    public function searchUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string'
        ]);

        $user = User::where('username', $request->username)->first();
        return $user
            ? response()->json($user)
            : response()->json(['error' => 'User not found'], 404);
    }
}
