<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PopupFormJumlahAyam extends Component
{
    public $isOpen = false;
    public $batchCode, $batchName, $docDate, $chickenQuantity;

    protected $listeners = ['openModal'];

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function save()
    {
        $this->validate([
            'batchCode' => 'required|string',
            'batchName' => 'required|string',
            'docDate' => 'required|date',
            'chickenQuantity' => 'required|integer',
        ]);

        session()->flash('message', 'Data populasi ayam berhasil disimpan.');
        $this->reset(['batchCode', 'batchName', 'docDate', 'chickenQuantity']);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.popup-form-jumlah-ayam');
    }
}

