<?php

namespace Modules\System\Livewire\Settings\Partials;

use Livewire\Component;
use Modules\System\Models\Setting;

class General extends Component
{
    public $settings = [
        'site_name' => '',
        'site_email' => '',
    ];

    public function mount()
    {
        $this->settings['site_name'] = Setting::getValue('site_name');
        $this->settings['site_email'] = Setting::getValue('site_email');
    }

    public function save()
    {
        $this->validate([
            'settings.site_name' => 'required|string|max:255',
            'settings.site_email' => 'nullable|email',
        ]);

        foreach ($this->settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $this->dispatch('notify', type: 'success', message: 'Đã lưu cấu hình chung');
    }

    public function render()
    {
        return view('System::livewire.settings.partials.general');
    }
}
