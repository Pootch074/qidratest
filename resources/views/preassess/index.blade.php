@extends('layouts.main')
@section('title', 'Preassess')
@section('header')
@endsection

@section('content')

<div class="w-full p-4 bg-[#cbdce8]">
    <div class="flex justify-center mt-8">
        <form id="queueForm" action="{{ route('queue.store') }}" method="POST">
            @csrf
            <button type="submit" 
                class="bg-blue-800 text-white px-6 py-3 rounded-xl shadow hover:bg-blue-700">
                Print New Queue Number
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('queueForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let form = this;

    fetch(form.action, {
        method: "POST",
        body: new FormData(form),
        headers: { "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Open a new window for printing
            let printWindow = window.open('', '', 'height=400,width=600');
            printWindow.document.write('<html><head><title>Queue Ticket</title></head><body>');
            printWindow.document.write('<h1 style="text-align:center;font-size:50px;">Queue #'+data.number+'</h1>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    });
});
</script>

@endsection

