
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
            <a href="{{ route('pacd') }}" 
               class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('pacd') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
               <img src="{{ Vite::asset('resources/images/icons/scan.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="ms-3">Scanned Clients</span>
            </a>
         </li>

         <li>
            <a href="{{ route('pacd.transactions.table') }}" 
               class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('pacd.transactions.table') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
               <img src="{{ Vite::asset('resources/images/icons/community.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="flex-1 ms-3 whitespace-nowrap">In Queue</span>
            </a>
         </li>

         <li>
            <a href="{{ route('pacd.sections.cards') }}" 
               class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('pacd.sections.cards') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
               <img src="{{ Vite::asset('resources/images/icons/ticket.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="flex-1 ms-3 whitespace-nowrap">Manual Ticket</span>
            </a>
         </li>

         <li>
            <a href="{{ route('pacd.pending.table') }}" 
               class="flex items-center p-2 text-white rounded-lg group
               {{ request()->routeIs('pacd.pending.table') ? 'bg-[#F03D46]' : 'hover:bg-[#5057c9]' }}">
               <img src="{{ Vite::asset('resources/images/icons/pause-circle.png') }}" 
                     alt="Users" 
                     class="shrink-0 w-7 h-7 transition duration-75 group-hover:opacity-80">
               <span class="flex-1 ms-3 whitespace-nowrap">Returnees</span>
            </a>
         </li>
      </ul>
   </div>
</aside>
