<?php

namespace App\Interfaces;

interface TodoRepositoryInterface
{
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function inviteUser($todoId, $username);
    public function addItem($todoId, $content, $userId);
}
