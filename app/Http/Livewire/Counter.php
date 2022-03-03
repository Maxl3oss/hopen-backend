<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Counter extends Component
{
    
    public $counter = 0;

    public function increment()
    {
        // dd("hello livewire"); // die dump
        $this->counter++;
    }

    public function decrement()
    {
        // dd("hello livewire"); // die dump
        $this->counter--;
    }

    public function resetCounter()
    {
        // dd("hello livewire"); // die dump
        $this->counter = 0;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
