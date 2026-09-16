<?php

namespace App\Livewire\Protocol;

use App\Models\Protocol;
use Livewire\Component;

class ShowSwitchboard extends Component
{
    public Protocol $protocol;

    public function render()
    {
        return view('livewire.protocol.show-switchboard');
    }
    public function mount(Protocol $protocol){
        
    }
}
