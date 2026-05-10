<?php

namespace Modules\System\Livewire\Settings;

use Livewire\Component;

class SettingForm extends Component
{
    public $tabs = [
        'theme' => 'Quản lý Themes',
        'general' => 'Cấu hình chung',        
        'menu' => 'Quản lý Menu',        
        'images'  => 'Hình ảnh',
        'seo'     => 'SEO/Mạng xã hội',
        'custom'  => 'Cấu hình tùy chỉnh',
    ];

    public $activeTab = 'theme';

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
            'theme' => 'admin.theme-switcher',
            'menu' => 'admin.header.menu-manager',
            'images'  => 'system.settings.partials.images',
            'seo'     => 'system.settings.partials.seo',
            'custom'  => 'system.settings.partials.custom',
            default   => 'system.settings.partials.theme',
        };
    }

    public function render()
    {
        return view('System::livewire.settings.setting-form');
    }
}
