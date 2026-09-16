<?php

namespace App\Livewire\Protocol;

use App\Models\Client;
use App\Models\Protocol;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class Create extends Component
{
    public Client $client;

    #[Validate('required')]
    public ?string $client_name = null;

    #[Validate('required')]
    public ?string $client_surname = null;

    #[Validate('required|min:3')]
    public ?string $client_address = null;

    // #[Validate('required')]
    public $client_phone = null;

    #[Validate('required')]
    public string $measurement_date;

    public ?string $next_measurement_date = null;

    public ?string $building_address = null;

    public ?string $description = null;

    public function render()
    {
        return view('livewire.protocol.create');
    }

    public function mount(Client $client): void
    {
        $this->client = $client;
        $this->client_name = $client->name;
        $this->client_surname = $client->surname;
        $this->client_address = $client->address;
        $this->client_phone = $client->phone;
        $this->building_address = $client->address;
        $this->measurement_date = today()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        $nextDateToSave = empty($this->next_date)
            ? Carbon::parse($this->measurement_date)->addYears(5)->format('d-m-Y')
            : $this->next_date;
        $additionalInfo = [
            'decision' => $this->description,
        ];
        $protocol = Protocol::create([
            'building_address' => $this->building_address,
            'additional_info' => $additionalInfo,
            'date' => $this->measurement_date,
            'next_date' => $nextDateToSave,
            'client_id' => $this->client->id,
        ]);

        return redirect()->route('protocol.show', ['protocol' => $protocol->id]);
    }
}
