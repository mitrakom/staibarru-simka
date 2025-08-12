<x-filament-panels::page>
    <div class="p-6 space-y-4">
        <x-filament::card>
            <table class="w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-600 uppercase text-sm font-medium">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Kode</th>
                        <th class="py-3 px-4 text-left">Nama Semester</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @foreach ($listSemester as $key => $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $key + 1 }}</td>
                            <td class="py-3 px-4">{{ $item->key }}</td>
                            <td class="py-3 px-4">{{ $item->value }}</td>
                            <td class="py-3 px-4 text-center">
                                <x-filament::button
                                    tag="a"
                                    href="{{ route('filament.mahasiswa.pages.detail-page', ['semester' => $item->key]) }}"
                                    icon="heroicon-o-arrow-right"
                                    icon-position="after"
                                    color="primary"
                                    size="sm"
                                >
                                    Detail
                                </x-filament::button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-filament::card>
    </div>
</x-filament-panels::page>
