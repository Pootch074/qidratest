@foreach ($clients as $client)
    <tr class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200">
        <td class="px-4 py-2">{{ $client->full_name }}</td>
        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($client->created_at)->format('M d Y, h:i A') }}</td>
        <td class="px-4 py-2">
            <button @click="selectedClientId={{ $client->id }}; showSectionModal=true"
                class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br 
                           focus:ring-1 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 
                           shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 
                           font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2
                           disabled:opacity-50 disabled:cursor-not-allowed">
                Generate Ticket
            </button>
        </td>
    </tr>
@endforeach

@section('scripts')
    <script>
        function refreshClientsTable() {
            fetch("{{ route('pacd.scanned_id.table') }}")
                .then(res => res.text())
                .then(html => {
                    document.getElementById("clients-table-body").innerHTML = html;
                })
                .catch(err => console.error("Error refreshing clients:", err));
        }

        setInterval(refreshClientsTable, 1000);
    </script>
@endsection
