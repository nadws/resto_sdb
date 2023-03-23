<?php

namespace App\Http\Livewire;

use App\Models\Karyawan;
use Livewire\Component;

class Absen extends Component
{
    public function increment()
    {
        dd(1);
    }

    public function render()
    {
        $data = [
            'karyawan' => Karyawan::all(),
            'bulan' => (int)date('m'),
            'tahun' => date('Y'),
        ];
        return view('livewire.absen', $data);
    }
    
}
