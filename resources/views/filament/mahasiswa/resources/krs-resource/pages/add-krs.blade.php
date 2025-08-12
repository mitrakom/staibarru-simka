<x-filament-panels::page>
    {{-- <x-filament::form wire:submit.prevent="save">
        {{ $this->form }}

        <x-filament::button type="submit">
            Simpan
        </x-filament::button>
    </x-filament::form> --}}

    {{-- <x-filament::section>
        <x-slot name="heading">
            Daftar Matakuliah
        </x-slot> --}}

        {{ $this->table }}
    {{-- </x-filament::section> --}}

    {{-- {{ app(\App\Services\SettingService::class)->get('semester_aktif') }} --}}

    {{-- <h1>Semester Aktif: {{ Setting::get('semester_aktif') }}</h1>
<p>Status krs: {{ Setting::get('krs_online_status', 'tidak aktif') }}</p> --}}


</x-filament-panels::page>
