
<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-[#2e3192]">
      <div class="flex justify-center mb-6">
         <img src="{{ Vite::asset('resources/images/dswd-white.png') }}" 
            alt="App Logo" 
            class="h-12 w-auto">
         <img src="{{ Vite::asset('resources/images/qidra-logo-white.png') }}" 
            alt="App Logo" 
            class="h-12 w-auto ml-4">
      </div>

      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{ route('admin') }}" class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('admin') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
               <img src="{{ Vite::asset('resources/images/icons/bar-chart-big.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="ms-3 text-white">Dashboard</span>
            </a>
         </li>

         <li>
            <a href="{{ route('admin.users') }}" class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('admin.users') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
               <img src="{{ Vite::asset('resources/images/icons/group.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="flex-1 ms-3 whitespace-nowrap text-white">Users</span>
            </a>
         </li>

         <li>
            <a href="{{ route('admin.steps') }}" class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('admin.steps') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
               <img src="{{ Vite::asset('resources/images/icons/horizontal-align-right.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="flex-1 ms-3 whitespace-nowrap text-white">Steps</span>
            </a>
         </li>

         <li>
            <a href="{{ route('admin.windows') }}" class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('admin.windows') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
              <img src="{{ Vite::asset('resources/images/icons/windows.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="flex-1 ms-3 whitespace-nowrap text-white">Windows</span>
            </a>
         </li>


      </ul>
   </div>
</aside>
