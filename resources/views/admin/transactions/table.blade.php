<table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3">Queue Number</th>
            <th scope="col" class="px-6 py-3">Client Type</th>
            <th scope="col" class="px-6 py-3">Window</th>
            <th scope="col" class="px-6 py-3">Status</th>
            <th scope="col" class="px-6 py-3">Created At</th>
            <th scope="col" class="px-6 py-3">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($transactions as $transaction)
            <tr class="bg-white border-b hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    {{-- Display number like R001, P002 --}}
                    {{ strtoupper(substr($transaction->client_type, 0, 1)) . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT) }}
                </td>
                <td class="px-6 py-4">
                    {{ ucfirst($transaction->client_type) }}
                </td>
                <td class="px-6 py-4">
                    {{ $transaction->window_id ?? '—' }}
                </td>
                <td class="px-6 py-4">
                    {{ ucfirst($transaction->queue_status) }}
                </td>
                <td class="px-6 py-4">
                    {{ $transaction->created_at->format('Y-m-d H:i') }}
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    {{-- <a href="{{ route('transactions.show', $transaction->id) }}" class="font-medium text-blue-600 hover:underline">View</a>
                    <a href="{{ route('transactions.edit', $transaction->id) }}" class="font-medium text-green-600 hover:underline">Edit</a>
                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="font-medium text-red-600 hover:underline" onclick="return confirm('Delete this transaction?')">
                            Delete
                        </button>
                    </form> --}}

                    <a href="" class="font-medium text-blue-600 hover:underline">View</a>
                    <a href="" class="font-medium text-green-600 hover:underline">Edit</a>
                    <form action="" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="font-medium text-red-600 hover:underline" onclick="return confirm('Delete this transaction?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    No transactions found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>