<?php

namespace App\View\Components;

use App\Libraries\StepNames;
use Illuminate\View\Component;

class StepSelect extends Component
{
    public string $name;

    public ?string $selected;

    public function __construct(string $name = 'step_name', ?string $selected = null)
    {
        $this->name = $name;
        $this->selected = $selected;
    }

    public function steps(): array
    {
        return StepNames::all();
    }

    public function render()
    {
        return view('components.step-select');
    }
}
