<!-- Modal -->
<div
    x-dialog
    x-model="open"
    style="display: none"
    class="fixed inset-0 z-50 overflow-y-auto"
    >
    <!-- Overlay -->
    <div x-dialog:overlay x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50"></div>

    <!-- Panel -->
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div
            x-dialog:panel
            x-transition.in x-transition.out.opacity
            class="relative w-full max-w-2xl overflow-y-auto bg-white rounded-lg shadow-lg"
            >
            <!-- Close Button -->
            {{-- <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" @click="$dialog.close()" class="p-2 text-gray-600 rounded-lg bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                    <span class="sr-only">Close modal</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div> --}}

            <!-- Panel -->
            {{--  class="p-8" --}}
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
