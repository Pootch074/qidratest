@foreach($clients as $client)
    <tr class="odd:bg-white even:bg-gray-50 hover:bg-indigo-50 transition duration-200">
        <td class="px-4 py-2">{{ $client->full_name }}</td>
        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($client->created_at)->format('M d Y, h:i A') }}</td>
        <td class="px-4 py-2">
            <button 
                @click="selectedClientId={{ $client->id }}; showSectionModal=true"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow">
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

