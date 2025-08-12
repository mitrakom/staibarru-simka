<div class="flex items-center space-x-3 bg-gray-100 px-3 py-2 rounded-lg shadow-sm">
    <span class="text-sm font-medium text-gray-700">
        @if ( Auth::user()->hasRole('admin') )
            <strong class="text-gray-900">{{ session('prodi_nama') }} - {{ session('semester')}} </strong>
        @elseif ( Auth::user()->hasRole('kaprodi'))
            <strong class="text-gray-900">{{ session('prodi_nama') }} - {{ session('semester')}} </strong>
        @else
        <strong class="text-gray-900">{{ session('prodi_nama') }} - {{ session('semester')}} </strong>
        @endif
    </span>
</div>

{{-- <livewire:topbar /> --}}