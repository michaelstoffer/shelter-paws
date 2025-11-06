<?php

namespace App\Livewire\Animals;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Animal;

#[Layout('layouts.livewire')]
class Index extends Component
{
    public function render(): View|Factory|\Illuminate\View\View
    {
        $animals = Animal::orderBy('name')->get();
        return view('livewire.animals.index', compact('animals'));
    }
}

