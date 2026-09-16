<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\User\Index;
use App\Livewire\User\Create;

Route::redirect('/','/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('dashboard', Index::class)->name('dashboard');
    Route::get('user/create', Create::class)->name('user.create');
    Route::get('protocol/create/{client}', App\Livewire\Protocol\Create::class)->name('protocol.create');
    Route::get('protocol/show/{protocol}', App\Livewire\Protocol\Show::class)->name('protocol.show');
    Route::get('protocol/switchborad-create/{switchboard}', App\Livewire\Protocol\CreateSwitchboard::class)->name('protocol.createSwitchboard');
    Route::get('protocol/rooms-form/{switchboard}', App\Livewire\Protocol\FormRoom::class)->name('protocol.formRoom');
    Route::get('protocol/wire-resistance-form/{protocol}', App\Livewire\Protocol\WireResistanceForm::class)->name('protocol.wire');
    Route::get('protocol/print/{protocol}', App\Livewire\Protocol\ProtocolPrint::class)->name('protocols.print');

});

require __DIR__.'/settings.php';
