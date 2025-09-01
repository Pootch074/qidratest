<table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Queue Number</th>
                        <th scope="col" class="px-6 py-3">Client Type</th>
                        <th scope="col" class="px-6 py-3">Step</th>
                        <th scope="col" class="px-6 py-3">Section</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            {{-- Queue Number --}}
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ strtoupper(substr($transaction->client_type, 0, 1)) . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- Client Type --}}
                            <td class="px-6 py-4">
                                {{ ucfirst($transaction->client_type) }}
                            </td>

                            {{-- Step --}}
                            <td class="px-6 py-4">
                                {{ $transaction->step_id ?? '—' }}
                            </td>

                            {{-- Section --}}
                            <td class="px-6 py-4">
                                {{ $transaction->section->section_name ?? '—' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                {{ ucfirst($transaction->queue_status) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>