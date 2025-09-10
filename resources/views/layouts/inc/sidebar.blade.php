{{-- <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button> --}}


<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-[#2e3192]">
      <div class="flex justify-center mb-6">
         <img src="{{ asset('assets/icons/dswd.png') }}" 
              alt="App Logo" 
              class="h-16 w-auto">
      </div>




      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{ route('admin') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-[#5057c9] group">
               <img src="{{ asset('assets/icons/bar-chart-big.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="ms-3 text-white">Dashboard</span>
            </a>
         </li>

         <li>
            <a href="{{ route('admin.users') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-[#5057c9] group">
               <img src="{{ asset('assets/icons/group.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="flex-1 ms-3 whitespace-nowrap text-white">Users</span>
            </a>
         </li>

         <li>
            <a href="{{ route('admin.steps') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-[#5057c9] group">
               <img src="{{ asset('assets/icons/horizontal-align-right.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="flex-1 ms-3 whitespace-nowrap text-white">Steps</span>
            </a>
         </li>

         <li>
            <a href="{{ route('admin.windows') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-[#5057c9] group">
              <img src="{{ asset('assets/icons/windows.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="flex-1 ms-3 whitespace-nowrap text-white">Windows</span>
            </a>
         </li>


      </ul>
   </div>
</aside>
