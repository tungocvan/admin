<div class="space-y-6 animate-fadeIn">
    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

        <div class="sm:col-span-4">
            <label class="block text-sm font-medium leading-6 text-gray-900">Tên cửa hàng (Site Name)</label>
            <div class="mt-2">
                <input type="text" wire:model="settings.site_name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>

        <div class="sm:col-span-3">
            <label class="block text-sm font-medium leading-6 text-gray-900">Hotline</label>
            <div class="mt-2">
                <input type="text" wire:model="settings.site_hotline" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>

        <div class="sm:col-span-3">
            <label class="block text-sm font-medium leading-6 text-gray-900">Email liên hệ</label>
            <div class="mt-2">
                <input type="email" wire:model="settings.site_email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>

        <div class="sm:col-span-6">
            <label class="block text-sm font-medium leading-6 text-gray-900">Địa chỉ kho/văn phòng</label>
            <div class="mt-2">
                <input type="text" wire:model="settings.site_address" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
    </div>
</div>
