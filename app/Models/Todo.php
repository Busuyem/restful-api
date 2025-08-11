<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['title', 'owner_id'];

    public function items()
    {
        return $this->hasMany(TodoItem::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function invitedUsers()
    {
        return $this->belongsToMany(User::class, 'todo_user', 'todo_id', 'user_id');
    }
}
