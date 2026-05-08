<?php

namespace Modules\Admin\Livewire;

use Livewire\Component;
use Modules\Admin\Support\ThemeManager;

class ThemeSwitcher extends Component
{
    public $current;

    public function mount(ThemeManager $theme)
    {
        $this->current = $theme->getThemeName();
    }

    public function change($theme, ThemeManager $manager)
    {
        $manager->set($theme);
        $this->current = $theme;

        $this->dispatch('theme-changed');
        //return redirect(request()->header('Referer'));
    }

    public function render(ThemeManager $theme)
    {
        return view('Admin::livewire.theme-switcher', [
            'themes' => $theme->all()
        ]);
    }
}