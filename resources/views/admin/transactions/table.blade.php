{{-- ✅ Fancy Transactions Table with Queue Number & Client Type Coloring --}}
<div class="overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">
    <h2 class="text-xl font-semibold text-gray-700 px-6 pt-4">Transactions</h2>

    <table class="min-w-full text-sm text-left border-collapse mt-2">
        <thead>
            <tr class="bg-gradient-to-r bg-[#2e3192] text-white">
                <th scope="col" class="px-6 py-3 font-semibold tracking-wide">Queue Number</th>
                <th scope="col" class="px-6 py-3 font-semibold tracking-wide">Client Type</th>
                <th scope="col" class="px-6 py-3 font-semibold tracking-wide">Step</th>
                <th scope="col" class="px-6 py-3 font-semibold tracking-wide">Section</th>
                <th scope="col" class="px-6 py-3 font-semibold tracking-wide">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($transactions as $transaction)
                @php
                    $queuePrefix = strtoupper(substr($transaction->client_type, 0, 1));
                    $queueNumber = $queuePrefix . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT);

                    // 🎨 Queue Number / Client Type Colors
                    $colorClass = match ($queuePrefix) {
                        'R' => 'text-blue-700 bg-blue-100',
                        'P' => 'text-red-700 bg-red-100',
                        default => 'text-gray-700 bg-gray-100',
                    };

                    // 🎨 Status Colors
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'ongoing' => 'bg-blue-100 text-blue-700',
                        'completed' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                    ];
                    $statusClass = $statusColors[strtolower($transaction->queue_status)] ?? 'bg-gray-100 text-gray-700';
                @endphp

                <tr class="hover:bg-indigo-50 transition duration-200">
                    {{-- Queue Number --}}
                    <td class="px-6 py-4 font-bold">
                        <span class="px-3 py-1 rounded-lg {{ $colorClass }}">
                            {{ $queueNumber }}
                        </span>
                    </td>

                    {{-- Client Type --}}
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                            {{ ucfirst($transaction->client_type) }}
                        </span>
                    </td>

                    {{-- Step --}}
                    <td class="px-6 py-4 text-gray-700">
                        {{ $transaction->step_id ?? '—' }}
                    </td>

                    {{-- Section --}}
                    <td class="px-6 py-4 text-gray-700">
                        {{ $transaction->section->section_name ?? '—' }}
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst($transaction->queue_status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                        🚫 No transactions found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
