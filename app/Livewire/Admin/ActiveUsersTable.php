<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

class ActiveUsersTable extends Component
{
    public $users;

    // Load users when the component mounts
    public function mount()
    {
        $this->loadUsers();
    }

    // Reload users
    public function loadUsers()
    {
        $this->users = User::with(['step', 'window'])
        ->where('status', 1)
        ->get();
    }

    // Delete user action
    public function deleteUser($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->delete();
            $this->loadUsers(); // refresh the table automatically
            session()->flash('message', 'User deleted successfully.');
        } else {
            session()->flash('error', 'User not found.');
        }
    }

    public function render()
    {
        return view('livewire.admin.active-users-table');
    }
}
