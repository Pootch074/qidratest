<div x-data="{ open: false }">
    <!-- Button to open the modal -->
    <a href="#" @click.prevent="openModal(false)"
        class="bg-[#DB0C16] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-xl">
        <span>Print Scoring</span>
        <img src="{{ Vite::asset('resources/assets/icons/icon-print.png') }}" class="h-5 w-5" alt="Print Scoring">
    </a>

    <!-- Modal -->
    <div x-show="showModal">
        <!-- Background backdrop -->
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true" @click="open = false">
        </div>

    </div>
</div>
