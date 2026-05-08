<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Quản lý Module</h2>
                <p class="text-sm text-gray-600 mt-1">Bật hoặc tắt các module trong hệ thống</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Tổng số:</span>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full">
                    {{ count($modules) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Modules by Type -->
    @php
        $groupedModules = collect($modules)->groupBy('type');
        $typeLabels = [
            'shell' => ['label' => 'Shell Modules', 'color' => 'bg-red-100 text-red-800', 'description' => 'Modules cốt lõi của hệ thống'],
            'support' => ['label' => 'Support Modules', 'color' => 'bg-yellow-100 text-yellow-800', 'description' => 'Modules hỗ trợ'],
            'domain' => ['label' => 'Domain Modules', 'color' => 'bg-blue-100 text-blue-800', 'description' => 'Modules nghiệp vụ']
        ];
    @endphp

    @foreach($groupedModules as $type => $typeModules)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <h3 class="text-lg font-medium text-gray-900">{{ $typeLabels[$type]['label'] ?? ucfirst($type) }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeLabels[$type]['color'] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $typeModules->count() }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">{{ $typeLabels[$type]['description'] ?? '' }}</p>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($typeModules as $module)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $module['name'] }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ ucfirst($module['type']) }} • {{ $module['source'] === 'manifest' ? 'Config' : 'Default' }}
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input
                                            type="checkbox"
                                            wire:click="toggleModule('{{ $module['name'] }}')"
                                            {{ $module['enabled'] ? 'checked' : '' }}
                                            class="sr-only peer"
                                        >
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <!-- Info Section -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Lưu ý</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Thay đổi trạng thái module sẽ có hiệu lực ngay lập tức. Một số module cốt lõi có thể không thể tắt được.</p>
                </div>
            </div>
        </div>
    </div>
</div>
