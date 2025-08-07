<div x-data="{ open: false }">
    <!-- Button to open the modal -->
    <a href="#" @click.prevent="openModal(false)"
        class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-xl">
        <img src="{{ asset('assets/icons/icon-plus.svg') }}" class="h-5 w-5" alt="Setup Assessment">
        <span>Setup Assessment</span>
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
                            x-text="editMode ? 'Setup Period and Team Selection' : 'Setup Period and Team Selection'"></h3>

                        <!-- Form -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                            <!-- Period -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm text-[#75777C]">Period</label>
                                <select type="text" x-model="rmt.period" name="period"
                                        class="mt-1 block w-full px-3 py-2 border border-[#CDCFD2] text-[#101828] rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </select>
                            </div>

                            <!-- LGU -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm text-[#75777C]">LGU</label>
                                <select type="text" x-model="rmt.lgu" name="lgu"
                                        class="mt-1 block w-full px-3 py-2 border border-[#CDCFD2] text-[#101828] rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </select>
                            </div>

                            <!-- Team Leader -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm text-[#75777C]">Team Leader</label>
                                <select type="text" x-model="rmt.team_leader" name="team_leader"
                                        class="mt-1 block w-full px-3 py-2 border border-[#CDCFD2] text-[#101828] rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </select>
                            </div>

                            <!-- Team Leader -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm text-[#75777C]">Members</label>
                                <select type="text" x-model="rmt.members" name="members"
                                        class="mt-1 block w-full px-3 py-2 border border-[#CDCFD2] text-[#101828] rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </select>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="py-3 sm:flex sm:flex-row-reverse mt-4">
                            <button @click="editMode ? updateUser() : addUser()" type="submit"
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
