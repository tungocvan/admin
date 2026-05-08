<?php

namespace Modules\System\Livewire\Settings;

use Livewire\Component;

class SettingForm extends Component
{
    public $tabs = [
        'general' => 'Cấu hình chung',
        'images'  => 'Hình ảnh',
        'seo'     => 'SEO/Mạng xã hội',
        'custom'  => 'Cấu hình tùy chỉnh',
    ];

    public $activeTab = 'general';

    public function setTab($tab)
    {
        if (!array_key_exists($tab, $this->tabs)) {
            $tab = 'general';
        }

        $this->activeTab = $tab;
    }

    public function getTabComponent()
    {
        return match ($this->activeTab) {
            'general' => 'system.settings.partials.general',
            'images'  => 'system.settings.partials.images',
            'seo'     => 'system.settings.partials.seo',
            'custom'  => 'system.settings.partials.custom',
            default   => 'system.settings.partials.general',
        };
    }

    public function render()
    {
        return view('System::livewire.settings.setting-form');
    }
}
