<?php

namespace Modules\Pharma\Livewire;

use App\Models\ActiveIngredient;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;
use Rap2hpoutre\FastExcel\FastExcel;

class Tthc extends Component
{
    use WithFileUploads;
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $sortColumn = 'stt';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    #[Url(history: true)]
    public string $hospitalLevel = '';

    #[Url(history: true)]
    public string $drugGroup = '';

    #[Url(history: true)]
    public string $dosageForm = '';

    #[Url(history: true)]
    public int $perPage = 10;

    public array $selected = [];
    public bool $selectAll = false;
    public bool $showFormModal = false;
    public bool $showImportModal = false;
    public ?int $editingId = null;
    public $importFile = null;
    public array $form = [];

    protected array $sortableColumns = [
        'stt',
        'name',
        'dosage_form',
        'hospital_level',
        'drug_group',
        'updated_at',
    ];

    public function mount(): void
    {
        $this->resetForm();
    }

    public function render(): View
    {
        $data = $this->baseQuery()->paginate($this->perPage);

        return view('Pharma::livewire.tthc', [
            'data' => $data,
            'groupOptions' => ActiveIngredient::query()
                ->whereNotNull('drug_group')
                ->orderBy('drug_group')
                ->distinct()
                ->pluck('drug_group'),
            'dosageOptions' => ActiveIngredient::query()
                ->whereNotNull('dosage_form')
                ->orderBy('dosage_form')
                ->distinct()
                ->pluck('dosage_form'),
            'hospitalOptions' => ActiveIngredient::query()
                ->whereNotNull('hospital_level')
                ->orderBy('hospital_level')
                ->distinct()
                ->pluck('hospital_level'),
            'hospitalLabels' => ActiveIngredient::HOSPITAL_LEVEL_LABELS,
        ]);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedHospitalLevel(): void
    {
        $this->resetPage();
    }

    public function updatedDrugGroup(): void
    {
        $this->resetPage();
    }

    public function updatedDosageForm(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        $this->selected = $value ? $this->currentPageIds() : [];
    }

    public function updatedSelected(): void
    {
        $currentPageIds = $this->currentPageIds();

        $this->selectAll = ! empty($currentPageIds)
            && count(array_intersect($currentPageIds, $this->selected)) === count($currentPageIds);
    }

    public function sortBy(string $column): void
    {
        abort_unless(in_array($column, $this->sortableColumns, true), 404);

        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function openCreateModal(): void
    {
        $this->editingId = null;
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $ingredient = ActiveIngredient::findOrFail($id);

        $this->editingId = $ingredient->id;
        $this->form = [
            'stt' => $ingredient->stt,
            'name' => $ingredient->name,
            'dosage_form' => $ingredient->dosage_form,
            'hospital_level' => $ingredient->hospital_level,
            'note' => $ingredient->note,
            'drug_group' => $ingredient->drug_group,
        ];

        $this->showFormModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), $this->messages());
        $payload = ActiveIngredient::sanitizePayload($validated['form']);

        if ($this->editingId) {
            ActiveIngredient::findOrFail($this->editingId)->update($payload);
            session()->flash('status', 'Đã cập nhật hoạt chất thành công.');
        } else {
            ActiveIngredient::create($payload);
            session()->flash('status', 'Đã tạo hoạt chất mới thành công.');
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function updateField(int $id, string $field, mixed $value): void
    {
        abort_unless(in_array($field, ['stt', 'name', 'dosage_form', 'hospital_level', 'note', 'drug_group'], true), 404);

        $ingredient = ActiveIngredient::findOrFail($id);

        $payload = ActiveIngredient::sanitizePayload([
            'stt' => $field === 'stt' ? $value : $ingredient->stt,
            'name' => $field === 'name' ? $value : $ingredient->name,
            'dosage_form' => $field === 'dosage_form' ? $value : $ingredient->dosage_form,
            'hospital_level' => $field === 'hospital_level' ? $value : $ingredient->hospital_level,
            'note' => $field === 'note' ? $value : $ingredient->note,
            'drug_group' => $field === 'drug_group' ? $value : $ingredient->drug_group,
            'source_file' => $ingredient->source_file,
        ]);

        $ingredient->update($payload);
    }

    public function delete(int $id): void
    {
        ActiveIngredient::findOrFail($id)->delete();

        session()->flash('status', 'Đã xóa bản ghi thành công.');
    }

    public function deleteSelected(): void
    {
        if ($this->selected === []) {
            return;
        }

        ActiveIngredient::whereIn('id', $this->selected)->delete();

        $count = count($this->selected);
        $this->selected = [];
        $this->selectAll = false;

        session()->flash('status', "Đã xóa {$count} bản ghi được chọn.");
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'hospitalLevel', 'drugGroup', 'dosageForm', 'selected', 'selectAll']);
        $this->sortColumn = 'stt';
        $this->sortDirection = 'asc';
        $this->perPage = 10;
        $this->resetPage();
    }

    public function import(): void
    {
        $validated = $this->validate([
            'importFile' => ['required', 'file', 'mimes:csv,txt,xlsx'],
        ], [
            'importFile.required' => 'Vui lòng chọn file CSV hoặc XLSX để import.',
            'importFile.mimes' => 'File import chỉ hỗ trợ định dạng CSV, TXT hoặc XLSX.',
        ]);

        $storedPath = $validated['importFile']->store('imports');
        $fullPath = Storage::disk('local')->path($storedPath);
        $source = basename($validated['importFile']->getClientOriginalName());
        $imported = 0;
        $skipped = 0;

        (new FastExcel())->import($fullPath, function (array $row) use (&$imported, &$skipped, $source): void {
            $payload = ActiveIngredient::sanitizePayload($this->mapImportRow($row, $source));

            if (blank($payload['name'])) {
                $skipped++;
                return;
            }

            $record = ActiveIngredient::withTrashed()->firstOrNew([
                'row_hash' => $payload['row_hash'],
            ]);

            $record->fill($payload);
            $record->deleted_at = null;
            $record->save();

            $imported++;
        });

        Storage::disk('local')->delete($storedPath);

        $this->showImportModal = false;
        $this->importFile = null;
        $this->resetPage();

        session()->flash('status', "Import hoàn tất. Imported: {$imported}. Skipped: {$skipped}.");
    }

    public function exportCurrentView()
    {
        $fileName = 'active-ingredients-' . now()->format('Ymd-His') . '.csv';
        $rows = $this->baseQuery()->get(['stt', 'name', 'dosage_form', 'hospital_level', 'note', 'drug_group']);

        return response()->streamDownload(function () use ($rows): void {
            $stream = fopen('php://output', 'w');

            fputcsv($stream, ['stt', 'name', 'dosage_form', 'hospital_level', 'note', 'drug_group']);

            foreach ($rows as $row) {
                fputcsv($stream, [
                    $row->stt,
                    $row->name,
                    $row->dosage_form,
                    $row->hospital_level,
                    $row->note,
                    $row->drug_group,
                ]);
            }

            fclose($stream);
        }, $fileName);
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->showImportModal = false;
        $this->resetValidation();
        $this->resetForm();
        $this->importFile = null;
    }

    protected function rules(): array
    {
        return [
            'form.stt' => ['nullable', 'integer', 'min:1'],
            'form.name' => ['required', 'string', 'max:255'],
            'form.dosage_form' => ['nullable', 'string', 'max:255'],
            'form.hospital_level' => ['nullable', 'integer', 'min:1', 'max:9'],
            'form.note' => ['nullable', 'string', 'max:65535'],
            'form.drug_group' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function messages(): array
    {
        return [
            'form.name.required' => 'Tên hoạt chất là trường bắt buộc.',
        ];
    }

    protected function resetForm(): void
    {
        $this->form = [
            'stt' => null,
            'name' => '',
            'dosage_form' => '',
            'hospital_level' => null,
            'note' => '',
            'drug_group' => '',
        ];
    }

    protected function baseQuery(): Builder
    {
        return ActiveIngredient::query()
            ->search($this->search)
            ->when($this->hospitalLevel !== '', fn (Builder $query) => $query->where('hospital_level', $this->hospitalLevel))
            ->when($this->drugGroup !== '', fn (Builder $query) => $query->where('drug_group', $this->drugGroup))
            ->when($this->dosageForm !== '', fn (Builder $query) => $query->where('dosage_form', $this->dosageForm))
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->orderBy('id', 'desc');
    }

    protected function currentPageIds(): array
    {
        return $this->baseQuery()
            ->forPage($this->getPage(), $this->perPage)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    protected function mapImportRow(array $row, string $source): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $normalized[strtolower(trim((string) $key))] = $value;
        }

        return [
            'stt' => $normalized['stt'] ?? $row[0] ?? null,
            'name' => $normalized['name'] ?? $row[1] ?? null,
            'dosage_form' => $normalized['dosage_form'] ?? $row[2] ?? null,
            'hospital_level' => $normalized['hospital_level'] ?? $row[3] ?? null,
            'note' => $normalized['note'] ?? $row[4] ?? null,
            'drug_group' => $normalized['drug_group'] ?? $row[5] ?? null,
            'source_file' => $source,
        ];
    }
}
