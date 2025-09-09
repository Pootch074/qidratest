<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;

class ClientForm extends Component
{
    public $full_name;

    protected $rules = [
        'full_name' => 'required|string|max:255',
    ];

    public function submit()
    {
        $this->validate();

        Client::create([
            'full_name' => $this->full_name,
            'ticket_status' => 'not_issued'
        ]);

        session()->flash('success', 'Client saved successfully!');

        $this->reset('full_name'); // clear input
    }

    public function render()
    {
        return view('livewire.client-form');
    }
}
