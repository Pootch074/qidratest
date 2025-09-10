@foreach($clients as $client)
    <tr>
        <td class="px-4 py-2 border-b">{{ $client->full_name }}</td>
        <td class="px-4 py-2 border-b">{{ \Carbon\Carbon::parse($client->created_at)->format('M d Y, h:i A') }}</td>
        <td class="px-4 py-2 border-b">
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

    setInterval(refreshClientsTable, 2000);
</script>
@endsection

