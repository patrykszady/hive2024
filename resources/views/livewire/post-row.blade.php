<tr class="text-left text-slate-900">
    <td class="py-4 pl-6 pr-3 font-medium">{{ $post->date->format('Y-m-d') }}</td>
    <td class="py-4 pl-4 text-left text-slate-500">{{ str($post->amount)->limit(50) }}</td>
    <td class="py-4 pl-4 pr-6 text-right">
        <x-dialog wire:model="showEditDialog">
            <x-dialog.button :class="'font-medium text-blue-600'">
                Edit
            </x-dialog.button>

            <x-dialog.panel>
                <form wire:submit="save" class="flex flex-col gap-4">
                    <h2 class="mb-1 text-3xl font-bold">Edit your post</h2>

                    <hr class="w-[75%]">

                    <label class="flex flex-col gap-2">
                        Title
                        <input autofocus wire:model="form.title" class="px-3 py-2 font-normal border border-gray-300 rounded-lg">
                        @error('form.title')<div class="text-sm font-normal text-red-500">{{ $message }}</div>@enderror
                    </label>

                    <label class="flex flex-col gap-2">
                        Content
                        <textarea wire:model="form.content" rows="5" class="px-3 py-2 font-normal border border-gray-300 rounded-lg"></textarea>
                        @error('form.content')<div class="text-sm font-normal text-red-500">{{ $message }}</div>@enderror
                    </label>

                    <x-dialog.footer>
                        <x-dialog.close-button>
                            <button type="button" class="px-6 py-2 font-semibold text-center text-gray-800 bg-gray-300 rounded-xl">Cancel</button>
                        </x-dialog.close-button>

                        <button type="submit" class="px-6 py-2 font-semibold text-center text-white bg-blue-500 rounded-xl disabled:cursor-not-allowed disabled:opacity-50">Save</button>
                    </x-dialog.footer>
                </form>
            </x-dialog.panel>
        </x-dialog>

        <x-dialog>
            <x-dialog.button :class="'font-medium text-red-600'">
                Delete
            </x-dialog.button>

            <x-dialog.panel>
                <div x-data="{ confirmation: '' }" class="flex flex-col gap-6">
                    <h2 class="text-3xl font-semibold">Are you sure you?</h2>
                    <h2 class="text-lg text-slate-700">This operation is permanant and can't be reversed. This post will be deleted forever.</h2>

                    <label class="flex flex-col gap-2">
                        Type "CONFIRM"
                        <input x-model="confirmation" class="px-3 py-2 border rounded-lg border-slate-300" placeholder="CONFIRM">
                    </label>

                    <x-dialog.footer>
                        <x-dialog.close-button>
                            <button class="px-6 py-2 text-lg font-semibold text-center rounded-xl bg-slate-300 text-slate-800">Cancel</button>
                        </x-dialog.close-button>
                        {{-- <button x-on:click="await $wire.delete({{ $post->id }}); $dialog.close()" wire:loading.class="opacity-50" class="px-6 py-2 text-lg font-semibold text-center text-white bg-red-500 rounded-xl disabled:cursor-not-allowed disabled:opacity-50">Delete</button> --}}
                        <x-dialog.close-button>
                            <button :disabled="confirmation != 'CONFIRM'" wire:click="$dispatch('deleted')" class="px-6 py-2 text-lg font-semibold text-center text-white bg-red-500 rounded-xl disabled:cursor-not-allowed disabled:opacity-50">Delete</button>
                        </x-dialog.close-button>
                    </x-dialog.footer>
                </div>
            </x-dialog.panel>
        </x-dialog>
    </td>
</tr>
