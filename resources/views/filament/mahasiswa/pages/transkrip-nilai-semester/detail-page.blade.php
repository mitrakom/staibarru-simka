<x-filament-panels::page>
    <div class="p-6 space-y-4">
        <x-filament::card>
            <table class="w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-600 uppercase text-sm font-medium">
                    <tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Kode</th>
                        <th class="py-3 px-4 text-left">Nama Matakuliah</th>
                        <th class="py-3 px-4 text-center">SKS</th>
                        <th class="py-3 px-4 text-center">Nilai Huruf</th>
                        <th class="py-3 px-4 text-center">Nilai Index</th>
                        <th class="py-3 px-4 text-center">S × N</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @foreach ($listNilai as $key => $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $key + 1 }}</td>
                            <td class="py-3 px-4">{{ $item->matakuliah->kode }}</td>
                            <td class="py-3 px-4">{{ $item->matakuliah->nama }}</td>
                            <td class="py-3 px-4 text-center ">{{ $item->matakuliah->sks }}</td>
                            <td class="py-3 px-4 text-center">{{ $item->nilai->huruf }}</td>
                            <td class="py-3 px-4 text-center">{{ $item->nilai->index }}</td>
                            <td class="py-3 px-4 text-center">{{ $item->nilai->index * $item->matakuliah->sks }}</td>
                        </tr>
                    @endforeach
                    <tr class="border-b border-gray-300 bg-gray-50 font-bold">
                        <td class="py-3 px-4 text-center" colspan="3">Jumlah</td>
                        <td class="py-3 px-4 text-center">{{ $this->getTotalSks() }}</td>
                        <td class="py-3 px-4 text-center"></td>
                        <td class="py-3 px-4 text-center"></td>
                        <td class="py-3 px-4 text-center">{{ $this->getTotalJumlah() }}</td>
                    </tr>
                    <tr class="border-b border-gray-300 bg-gray-50 font-bold">
                        <td class="py-3 px-4 text-center" colspan="3">Index Prestasi Semester</td>
                        <td class="py-3 px-4 text-center">{{ $this->getIps() }}</td>
                        <td class="py-3 px-4 text-center"></td>
                        <td class="py-3 px-4 text-center"></td>
                        <td class="py-3 px-4 text-center"></td>
                    </tr>
                </tbody>
            </table>
        </x-filament::card>
    </div>
</x-filament-panels::page>
