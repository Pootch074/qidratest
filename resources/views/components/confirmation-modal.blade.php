@props(['id' => 'confirmationModal', 'title' => 'Confirm Action', 'message' => 'Are you sure?', 'confirmText' => 'Confirm', 'cancelText' => 'Cancel'])

<div id="{{ $id }}" 
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50"
     role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title" aria-describedby="{{ $id }}-desc">
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 id="{{ $id }}-title" class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $title }}
            </h2>
        </div>
        
        <div class="px-6 py-4">
            <p id="{{ $id }}-desc" class="text-gray-700 dark:text-gray-300">
                {{ $message }}
            </p>
        </div>
        
        <div class="px-6 py-4 flex justify-end space-x-3 border-t border-gray-200 dark:border-gray-700">
            <button type="button" 
                    class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white hover:bg-gray-300"
                    data-modal-cancel>
                {{ $cancelText }}
            </button>
            <button type="button" 
                    class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700"
                    data-modal-confirm>
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>
