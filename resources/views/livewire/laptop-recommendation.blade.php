<div> <div class="input-section grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                <span class="bg-blue-100 text-blue-600 py-1 px-2 rounded text-sm mr-2">1</span>
                Kriteria
            </h3>
            
            <div class="space-y-3 mb-4 bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" wire:model="newCriteriaName" placeholder="Nama Kriteria" class="border p-2 rounded text-sm w-full">
                    <select wire:model="newCriteriaType" class="border p-2 rounded text-sm w-full">
                        <option value="benefit">Benefit</option>
                        <option value="cost">Cost</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <input type="number" step="0.01" wire:model="newCriteriaWeight" placeholder="Bobot" class="border p-2 rounded text-sm w-full">
                    <button wire:click="addCriteria" class="bg-blue-600 text-white px-3 py-2 rounded text-sm">Add</button>
                </div>
            </div>

            <div class="space-y-1 max-h-32 overflow-y-auto">
                @foreach($criterias as $c)
                    <div class="flex justify-between bg-white border p-2 rounded text-xs">
                        <span>{{ $c->name }} ({{ $c->type }}) - {{ $c->weight }}</span>
                        <button wire:click="deleteCriteria({{ $c->id }})" class="text-red-500 font-bold">&times;</button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                <span class="bg-green-100 text-green-600 py-1 px-2 rounded text-sm mr-2">2</span>
                Alternatif
            </h3>

            <div class="flex gap-2 mb-4 bg-gray-50 p-4 rounded-lg">
                <input type="text" wire:model="newAlternativeName" placeholder="Nama Laptop" class="border p-2 rounded text-sm w-full">
                <button wire:click="addAlternative" class="bg-green-600 text-white px-3 py-2 rounded text-sm">Add</button>
            </div>

            <div class="space-y-1 max-h-32 overflow-y-auto">
                @foreach($alternatives as $alt)
                    <div class="flex justify-between bg-white border p-2 rounded text-xs">
                        <span>{{ $alt->name }}</span>
                        <button wire:click="deleteAlternative({{ $alt->id }})" class="text-red-500 font-bold">&times;</button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if($criterias->count() > 0 && $alternatives->count() > 0)
    <div class="input-section bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="font-bold text-lg text-gray-800 mb-4">3. Input Nilai Spesifikasi</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Laptop</th>
                        @foreach($criterias as $c)
                            <th class="p-2 border text-center">{{ $c->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatives as $alt)
                        <tr>
                            <td class="p-2 border font-medium">{{ $alt->name }}</td>
                            @foreach($criterias as $c)
                                @php
                                    $val = $alt->evaluations->where('criteria_id', $c->id)->first()->value ?? 0;
                                @endphp
                                <td class="p-1 border text-center">
                                    <input type="number" step="0.01"
                                           class="w-full text-center outline-none focus:bg-blue-50"
                                           value="{{ $val }}"
                                           wire:change="updateScore({{ $alt->id }}, {{ $c->id }}, $event.target.value)">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if(count($results) > 0)
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-blue-600 p-6 print:border-none print:shadow-none">
        <div class="hidden print:block text-center mb-6">
            <h1 class="text-2xl font-bold">Laporan Rekomendasi Laptop</h1>
            <p class="text-sm text-gray-500">Metode TOPSIS - Tanggal: {{ date('d-m-Y') }}</p>
        </div>

        <h3 class="font-bold text-xl text-gray-800 mb-4 no-print">4. Hasil Perankingan</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 border border-gray-300 text-center w-16">Rank</th>
                        <th class="p-3 border border-gray-300">Nama Laptop</th>
                        <th class="p-3 border border-gray-300 text-center">Nilai Preferensi (V)</th>
                        <th class="p-3 border border-gray-300 text-sm">Detail (D+ / D-)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $res)
                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="p-3 border border-gray-300 text-center font-bold">#{{ $index + 1 }}</td>
                            <td class="p-3 border border-gray-300 font-medium">{{ $res['name'] }}</td>
                            <td class="p-3 border border-gray-300 text-center font-bold text-blue-700">
                                {{ number_format($res['score'], 4) }}
                            </td>
                            <td class="p-3 border border-gray-300 text-xs text-gray-600 font-mono">
                                D+: {{ $res['detail']['d_plus'] }} <br>
                                D-: {{ $res['detail']['d_min'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="hidden print:block mt-10 text-right">
            <p>Mengetahui,</p>
            <br><br><br>
            <p class="font-bold">( Admin Sistem )</p>
        </div>
    </div>
    @endif
</div>