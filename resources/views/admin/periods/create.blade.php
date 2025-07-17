<div x-data="{ open: false }">
    <!-- Button to open the modal -->
    <a href="#" @click.prevent="openModal(false)"
        class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-xl">
        <img src="{{ asset('build/assets/icons/icon-plus.svg') }}" class="h-5 w-5" alt="New Period">
        <span>New Period</span>
    </a>

    <!-- Modal -->
    <div x-show="showModal">
        <!-- Background backdrop -->
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true" @click="open = false">
        </div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal panel -->
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4" id="modal-title"
                            x-text="editMode ? 'Edit Period' : 'Add New Period'"></h3>

                        <!-- Form -->
                        <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 mb-2">
                            <!-- Period Name -->
                            <div>
                                <label class="block text-sm text-[#75777C]">Period Name</label>
                                <input type="text" x-model="newP.name" name="name" required
                                       class="mt-1 block w-full px-3 py-2 border border-[#CDCFD2] text-[#101828] rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <!-- Last Name -->
                            <div>
                                <label class="block text-sm text-[#75777C]">Period Start Date</label>
                                <input type="date" x-model="newP.start_date" name="start_date"
                                       class="mt-1 block w-full px-3 py-2 border border-[#CDCFD2] text-[#101828] rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm text-[#75777C]">Period End Date</label>
                                <input type="date" x-model="newP.end_date" name="end_date"
                                       class="mt-1 block w-full px-3 py-2 border border-[#CDCFD2] text-[#101828] rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="py-3 sm:flex sm:flex-row-reverse mt-4">
                            <button @click="editMode ? updateP() : addP()" type="submit"
                                class="inline-flex w-30 justify-center rounded-lg bg-[#2E3192] px-3 py-4 text-[18px] font-semibold text-white shadow-xs hover:bg-blue-800 sm:ml-3">
                                <span x-text="editMode ? 'Update' : 'Save'"></span>
                            </button>
                            <a @click="showModal = false"
                                class="mt-3 inline-flex w-30 justify-center rounded-lg bg-white px-3 py-4 text-[18px] font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0">
                                Cancel
                            </a>
                        </div>
                        <!-- End Form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
