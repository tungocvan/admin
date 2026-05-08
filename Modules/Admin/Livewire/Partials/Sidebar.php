<?php

namespace Modules\Admin\Livewire\Partials;

use Livewire\Component;
use Modules\Admin\Services\SidebarService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Modules\Admin\Models\Setting;
use Modules\Admin\Support\ThemeManager;

class Sidebar extends Component
{
    // ======================
    // STATE
    // ======================
    public $menus = [];
    public $theme = [];
    public $sidebarOpen = true;
    public $titleSidebar = '';

    // ======================
    // DEFAULT THEME (SAFE FALLBACK)
    // ======================
    protected array $defaultTheme = [

        // ======================
        // BASE
        // ======================
        'background'        => 'bg-slate-50',

        // 🔥 FIX: tăng readability
        'text'              => 'text-slate-700',

        'hover'             => 'hover:bg-slate-100',

        // ======================
        // ACTIVE STATE
        // ======================
        'active_bg'         => 'bg-indigo-600',
        'active_text'       => 'text-white',

        // ======================
        // ICON
        // ======================
        'icon_active'       => 'text-indigo-600',
        'icon_inactive'     => 'text-slate-400',

        // ======================
        // CHILD MENU (FIX QUAN TRỌNG)
        // ======================
        'child_text'        => 'text-slate-600', // 🔥 FIX: 500 → 600 (dễ đọc hơn)

        'child_hover'       => 'hover:bg-slate-100 hover:text-slate-900',

        'child_active_bg'   => 'bg-indigo-500/10',
        'child_active_text' => 'text-indigo-600',

        // ======================
        // BORDER
        // ======================
        'border'            => 'border-slate-200',
    ];
  
    // ======================
    // MOUNT
    // ======================
    public function mount(SidebarService $service, ThemeManager $themeManager)
    {
        $this->menus = $service->getMenus();

        $this->titleSidebar = Setting::getValue('title_sidebar') ?? 'Admin';
        // $config = File::getRequire(
        //     base_path('Modules/Admin/config/sidebar.php')
        // );
        // $themeName = auth()->user()?->theme
        //     ?? ($config['theme'] ?? 'soft-light');

        // $themes = $config['themes'] ?? [];

        // $selectedTheme = $themes[$themeName] ?? [];

        // $this->theme = array_merge(
        //     $this->defaultTheme,
        //     $selectedTheme
        // );
         $this->theme = $themeManager->get();

        $this->sidebarOpen = session('sidebar_open', true);
    }

    // ======================
    // TOGGLE SIDEBAR
    // ======================
    public function toggleSidebar()
    {
        $this->sidebarOpen = !$this->sidebarOpen;

        session(['sidebar_open' => $this->sidebarOpen]);
    }

    // ======================
    // RENDER
    // ======================
    public function render()
    {
        return view('Admin::livewire.partials.sidebar');
    }
}
