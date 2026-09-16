<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\Layout; 
use App\Models\Client;
use Flux;
#[Layout('layouts.app')]
class Index extends Component
{
    public $clients;
    public $isEditing= [];
    public array $formData = [];
    public function render()
    {
        return view('livewire.user.index');
    }

    public function mount(){
        $this->clients = Client::with('protocols')->get();
    }
    public function toggleEdit($clientId)
    {
        if (!isset($this->isEditing[$clientId]) || !$this->isEditing[$clientId]) {
            $client = Client::find($clientId);
            $this->formData[$clientId] = [
                'name' => $client->name,
                'surname' => $client->surname,
                'phone' => $client->phone,
                'address' => $client->address,
            ];
            $this->isEditing[$clientId] = true;
        } else {
            $this->isEditing[$clientId] = false;
        }
    }
    public function update($clientId){
        $client = Client::find($clientId);
        $client->update($this->formData[$clientId]);
        $this->isEditing[$clientId] = false;
    }
    public function removeClient($clientId){
        $client = Client::find($clientId);
        $client->delete();
                Flux::toast(
            text: 'Usunięto użytkownika',
            heading: 'Usunięto',
            variant: 'danger'
        );
    }

}
