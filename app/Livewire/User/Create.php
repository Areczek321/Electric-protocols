<?php

namespace App\Livewire\User;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Models\Client;

#[Layout('layouts.app')]
class Create extends Component
{
    #[Validate('required')] 
    public $name;
    #[Validate('required')] 
    public $surname;
    #[Validate('required|min:3')] 
    public $address;
    // #[Validate('required')] 
    public $phone;

    public function render()
    {
        return view('livewire.user.create');
    }
    public function store(){
        $validated = $this->validate();
        if($this->phone==""){$this->phone=null;}
        Client::create([
            'name'=>$this->name,
            'surname'=>$this->surname,
            'address'=>$this->address,
            'phone'=>$this->phone,
        ]);
        redirect()->route('dashboard');
    }
}
