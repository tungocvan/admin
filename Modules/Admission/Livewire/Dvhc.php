<?php

namespace Modules\Admission\Livewire;

use Livewire\Component;
use Modules\Admission\Models\AdmissionLocation;

class Dvhc extends Component
{
    public $search = '';

    public $selectedProvince = '';
    public $editingProvinceName = '';

    public $provinces = [];
    public $wards = [];
    public $rows = [];

    public function mount()
    {
        $this->loadProvinces();
        $this->loadData();
    }

    // =========================
    // LOAD PROVINCES
    // =========================
    public function loadProvinces()
    {
        $this->provinces = AdmissionLocation::query()
            ->select('province_name')
            ->distinct()
            ->orderBy('province_name')
            ->get()
            ->toArray();
    }

    // =========================
    // CHỌN TỈNH
    // =========================
    public function updatedSelectedProvince()
    {
        if (!$this->selectedProvince) return;

        $this->editingProvinceName = $this->selectedProvince;

        $this->wards = AdmissionLocation::where('province_name', $this->selectedProvince)
            ->orderBy('ward_name')
            ->get()
            ->toArray();

        $this->loadData();
    }

    // =========================
    // UPDATE TỈNH (ALL ROWS)
    // =========================
    public function updateProvinceName()
    {
        if (!$this->selectedProvince || !$this->editingProvinceName) return;

        AdmissionLocation::where('province_name', $this->selectedProvince)
            ->update([
                'province_name' => $this->editingProvinceName
            ]);

        $this->selectedProvince = $this->editingProvinceName;

        $this->loadProvinces();
        $this->loadData();
    }

    // =========================
    // UPDATE PHƯỜNG
    // =========================
    public function updateRow($index)
    {
        $row = $this->rows[$index] ?? null;

        if (!$row) return;

        AdmissionLocation::where('id', $row['id'])->update([
            'ward_name' => $row['ward_name'],
        ]);
    }

    // =========================
    // LOAD TABLE
    // =========================
    public function updatedSearch()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->rows = AdmissionLocation::query()

            ->when($this->selectedProvince, fn($q) =>
                $q->where('province_name', $this->selectedProvince)
            )

            ->when($this->search, function ($q) {
                $q->where('ward_name', 'like', "%{$this->search}%");
            })

            ->orderBy('ward_name')
            ->limit(200)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('Admission::livewire.dvhc');
    }
}