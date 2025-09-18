@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-gray-200">
    <div class="p-4 sm:ml-64">
        {{-- ✅ Add User Modal --}}
        <div id="addUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 transform transition-transform duration-300 scale-95">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800">Add New User</h3>
                    <button id="closeAddUserModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
                </div>

                <form id="addUserForm" class="space-y-5">
                    @csrf
                    {{-- First & Last Name --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">First Name</label>
                            <input type="text" name="first_name" required 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Last Name</label>
                            <input type="text" name="last_name" required 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <input type="email" name="email" required 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- Position & User Type --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Position</label>
                            <select 
                                name="position" 
                                required 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="" disabled selected>-- Select Position --</option>
                                <option value="Accountant III">Accountant III</option>
                                <option value="Administrative Aide I">Administrative Aide I</option>
                                <option value="Administrative Aide II">Administrative Aide II</option>
                                <option value="Administrative Aide III">Administrative Aide III</option>
                                <option value="Administrative Aide IV">Administrative Aide IV</option>
                                <option value="Administrative Aide V">Administrative Aide V</option>
                                <option value="Administrative Aide VI">Administrative Aide VI</option>
                                <option value="Administrative Assistant I">Administrative Assistant I</option>
                                <option value="Administrative Assistant II">Administrative Assistant II</option>
                                <option value="Administrative Assistant III">Administrative Assistant III</option>
                                <option value="Administrative Assistant III (Bookkeeper)">Administrative Assistant III (Bookkeeper)</option>
                                <option value="Administrative Officer I">Administrative Officer I</option>
                                <option value="Administrative Officer II">Administrative Officer II</option>
                                <option value="Administrative Officer III">Administrative Officer III</option>
                                <option value="Administrative Officer IV">Administrative Officer IV</option>
                                <option value="Administrative Officer V">Administrative Officer V</option>
                                <option value="Area Coordinator">Area Coordinator</option>
                                <option value="Budget Assistant">Budget Assistant</option>
                                <option value="Cash Clerk">Cash Clerk</option>
                                <option value="Chief Administrative Officer">Chief Administrative Officer</option>
                                <option value="Community Development Assistant II">Community Development Assistant II</option>
                                <option value="Community Development Officer II">Community Development Officer II</option>
                                <option value="Community Development Officer III">Community Development Officer III</option>
                                <option value="Community Development Officer IV">Community Development Officer IV</option>
                                <option value="Community Development Officer V">Community Development Officer V</option>
                                <option value="Community Empowerment Facilitator">Community Empowerment Facilitator</option>
                                <option value="Community Facilitator">Community Facilitator</option>
                                <option value="Community Facilitator Aide">Community Facilitator Aide</option>
                                <option value="Encoder">Encoder</option>
                                <option value="Executive Assistant">Executive Assistant</option>
                                <option value="Financial Analyst I">Financial Analyst I</option>
                                <option value="Financial Analyst II">Financial Analyst II</option>
                                <option value="Financial Analyst III">Financial Analyst III</option>
                                <option value="Houseparent I">Houseparent I</option>
                                <option value="Houseparent II">Houseparent II</option>
                                <option value="Houseparent III">Houseparent III</option>
                                <option value="Management and Audit Analyst II">Management and Audit Analyst II</option>
                                <option value="Manpower Development Officer I">Manpower Development Officer I</option>
                                <option value="Manpower Development Officer II">Manpower Development Officer II</option>
                                <option value="Medical Officer IV">Medical Officer IV</option>
                                <option value="Monitoring &amp; Evaluation Officer II">Monitoring &amp; Evaluation Officer II</option>
                                <option value="Monitoring &amp; Evaluation Officer III">Monitoring &amp; Evaluation Officer III</option>
                                <option value="Municipal Monitor">Municipal Monitor</option>
                                <option value="Notifier">Notifier</option>
                                <option value="Planning Officer I">Planning Officer I</option>
                                <option value="Planning Officer II">Planning Officer II</option>
                                <option value="Planning Officer III">Planning Officer III</option>
                                <option value="Planning Officer IV">Planning Officer IV</option>
                                <option value="Procurement Officer">Procurement Officer</option>
                                <option value="Project Development Officer I">Project Development Officer I</option>
                                <option value="Project Development Officer II">Project Development Officer II</option>
                                <option value="Project Development Officer III">Project Development Officer III</option>
                                <option value="Project Development Officer IV">Project Development Officer IV</option>
                                <option value="Project Development Officer V">Project Development Officer V</option>
                                <option value="Project Evaluation Officer III">Project Evaluation Officer III</option>
                                <option value="Project Evaluation Officer IV">Project Evaluation Officer IV</option>
                                <option value="Psychologist I">Psychologist I</option>
                                <option value="Social Marketing Officer">Social Marketing Officer</option>
                                <option value="Social Welfare Aide">Social Welfare Aide</option>
                                <option value="Social Welfare Assistant">Social Welfare Assistant</option>
                                <option value="Social Welfare Officer I">Social Welfare Officer I</option>
                                <option value="Social Welfare Officer II">Social Welfare Officer II</option>
                                <option value="Social Welfare Officer III">Social Welfare Officer III</option>
                                <option value="Social Welfare Officer IV">Social Welfare Officer IV</option>
                                <option value="Social Welfare Officer V">Social Welfare Officer V</option>
                                <option value="Statistician Aide">Statistician Aide</option>
                                <option value="Statistician II">Statistician II</option>
                                <option value="Supervising Administrative Officer">Supervising Administrative Officer</option>
                                <option value="Teacher (ECCD)">Teacher (ECCD)</option>
                                <option value="Technical Facilitator">Technical Facilitator</option>
                                <option value="Training Assistant">Training Assistant</option>
                                <option value="Training Specialist I">Training Specialist I</option>
                                <option value="Training Specialist II">Training Specialist II</option>
                                <option value="Training Specialist III">Training Specialist III</option>
                                <option value="Training Specialist IV">Training Specialist IV</option>
                                <option value="Utility Worker">Utility Worker</option>
                                <option value="Utility Worker II">Utility Worker II</option>
                                <option value="Validator">Validator</option>
                                <option value="Director II">Director II</option>
                                <option value="Director III">Director III</option>
                                <option value="Director IV">Director IV</option>
                            </select>
                        </div>

                        {{-- put assign step here --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Assign Step</label>
                            <select name="step_id" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled selected>-- Select Step --</option>
                                @foreach($steps as $step)
                                    <option value="{{ $step->id }}">{{ $step->step_number }} - {{ $step->step_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Assign Step --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Assign Window</label>
                            <select name="window_id" required disabled
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled selected>-- Select Window --</option>
                            </select>
                        </div>
                
@if(auth()->user()->section_id == 15)
    <div id="assignCategoryWrapper">
        <label class="block text-sm font-medium text-gray-600">Assign Category</label>
        <select id="assignedCategorySelect" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="" disabled selected>-- Select Category --</option>
            <option value="regular">Regular</option>
            <option value="priority">Priority</option>
            <option value="both">Both</option>
        </select>
    </div>
@endif

{{-- Hidden field, no name by default --}}
<input type="hidden" id="assignedCategoryHidden" value="">


                    </div>

                    {{-- Password --}}
                    <div x-data="{ show: false }" class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                        
                        <input 
                            id="password" 
                            name="password" 
                            :type="show ? 'text' : 'password'" 
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter password">
                        
                        <!-- Eye toggle button -->
                        <button type="button" 
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center pt-4 pr-3 text-gray-500 focus:outline-none">

                            <!-- Closed eye -->
                            <img x-show="!show" 
                                src="{{ Vite::asset('resources/images/icons/eye-close.png') }}" 
                                alt="Show Password" 
                                class="h-5 w-5">

                            <!-- Open eye -->
                            <img x-show="show" 
                                src="{{ Vite::asset('resources/images/icons/eye-open.png') }}" 
                                alt="Hide Password" 
                                class="h-5 w-5">
                        </button>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelAddUser" 
                                class="text-white bg-gradient-to-r from-gray-400 via-gray-500 to-gray-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-gray-300 dark:focus:ring-gray-800 shadow-lg shadow-gray-500/50 dark:shadow-lg dark:shadow-gray-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Cancel</button>
                        <button type="submit" 
                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
            {{-- Header & Add User --}}
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Users</h2>
                <button id="openAddUserModal" 
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    Add User
                </button>
            </div>
            <div class="overflow-x-auto flex-1">
                <table id="usersTable" class="min-w-full divide-y divide-gray-200 text-gray-700">
                    <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                        <tr>
                            @foreach($userColumns as $label)
                                <th class="px-6 py-3 font-semibold tracking-wide">{{ $label }}</th>
                            @endforeach
                            <th class="px-6 py-3 font-semibold tracking-wide">Assigned Step</th>
                            <th class="px-6 py-3 font-semibold tracking-wide">Assigned Window</th>
                            <th class="px-6 py-3 font-semibold tracking-wide text-center rounded-tr-lg">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                        @forelse ($users as $u)
                            <tr class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200">
                                @foreach($userColumns as $field => $label)
                                    <td class="px-6 py-3 text-gray-700">
                                        @if($field === 'user_type')
                                            {{ $u->getUserTypeName() }}
                                        @else
                                            {{ $u->$field ?? '—' }}
                                        @endif
                                    </td>
                                @endforeach


                                {{-- Assigned Step --}}
                                <td class="px-6 py-3 font-medium text-gray-700">
                                    {{ $u->step->step_number ?? '—' }}
                                </td>

                                {{-- Assigned Window --}}
                                <td class="px-6 py-3 font-medium text-gray-700">
                                    {{ $u->window->window_number ?? '—' }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-3 text-center space-x-2">
                                    
                                    <button onclick="deleteUser({{ $u->id }})"
                                            class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($userColumns) + 3 }}" class="px-6 py-3 text-center text-gray-500">
                                    🚫 No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.appBaseUrl = "{{ url('') }}";
    window.userColumnsCount = {{ count($userColumns) + 3 }};
</script>
@vite('resources/js/adminUsers.js')
@endsection
