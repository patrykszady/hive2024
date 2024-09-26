<ui-toast x-data x-on:toast-show.document="$el.showToast($event.detail)" wire:ignore>
    <template>
        <dialog popover="manual" class="m-0 ml-auto mt-auto pr-6 pb-6 bg-transparent">
            <div class="p-2 rounded-xl shadow-sm bg-white border border-zinc-200 dark:bg-zinc-700 dark:border-zinc-600">
                <div class="flex items-center gap-4">
                    <div class="flex-1 pl-3 py-2">
                        <div class="font-medium text-sm text-zinc-800 dark:text-white [&:not(:empty)+div]:font-normal [&:not(:empty)+div]:text-zinc-500 [&:not(:empty)+div]:dark:text-zinc-300 [&:not(:empty)]:pb-2"><slot name="heading"></slot></div>

                        <div class="font-medium text-sm text-zinc-800 dark:text-white"><slot name="text"></slot></div>
                    </div>

                    <div class="flex items-center">
                        <button type="button" onclick="this.closest('ui-toast').hideToast()" class="inline-flex items-center font-medium justify-center gap-2 truncate disabled:opacity-50 dark:disabled:opacity-75 disabled:cursor-default h-8 text-sm rounded-md w-8 bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15 text-zinc-400 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-white" as="button">
                            <div>
                                <svg class="[:where(&amp;)]:size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </dialog>
    </template>
</ui-toast>
