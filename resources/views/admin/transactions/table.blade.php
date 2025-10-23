{{-- ✅ Fancy Transactions Table with Queue Number & Client Type Coloring --}}
<div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">In Queue Clients</h2>
        <div class="overflow-x-auto flex-1">
            <table class="min-w-full divide-y divide-gray-200 text-gray-700">
                <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tl-lg">Queue Number</th>
                        {{-- <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Full Name</th> --}}
                        <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Client Type</th>
                        <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Step</th>
                        <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Section</th>
                        <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tr-lg">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                    @forelse ($transactions as $transaction)
                        <tr class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200">
                            <td class="px-6 py-4 font-semibold">
                                @php
    switch (strtolower($transaction->client_type->value)) {
        case 'priority':
            $prefix = 'P';
            break;
        case 'regular':
            $prefix = 'R';
            break;
        case 'deferred':
            $prefix = 'D';
            break;
        default:
            $prefix = strtoupper(substr($transaction->client_type->value, 0, 1));
    }
@endphp
{{ $prefix . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT) }}


                            </td>
                            {{-- <td class="px-6 py-4">{{ $transaction->full_name ?? '—' }}</td> --}}
                            <td class="px-6 py-4">
                                @if (strtolower($transaction->client_type->value) === 'priority')
    <span class="px-2 py-1 rounded-full text-white text-xs bg-[#ee1c25]">
        Priority
    </span>
@elseif (strtolower($transaction->client_type->value) === 'regular')
    <span class="px-2 py-1 rounded-full text-white text-xs bg-[#2e3192]">
        Regular
    </span>
@elseif (strtolower($transaction->client_type->value) === 'deferred')
    <span class="px-2 py-1 rounded-full text-black text-xs bg-[#fef200]">
        Returnee
    </span>
@endif

                            </td>


                            <td class="px-6 py-4">{{ $transaction->step->step_number ?? '—' }}</td>
                            <td class="px-6 py-4">{{ $transaction->section->section_name ?? '—' }}</td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'waiting' => 'bg-yellow-400 text-yellow-900',
                                        'pending' => 'bg-orange-400 text-orange-900',
                                        'serving' => 'bg-green-500 text-white',
                                    ];
                                    $statusClass = $statusColors[strtolower($transaction->queue_status->value ?? $transaction->queue_status)] ?? 'bg-gray-300 text-gray-700';

                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($transaction->queue_status->value ?? $transaction->queue_status) }}

                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 font-medium">
                                🚫 No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
