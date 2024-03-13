<div>
    <x-dialog wire:model="show">
        <x-dialog.button>
            New Post
        </x-dialog.button>

        <x-dialog.panel>
            <form wire:submit="add" class="flex flex-col gap-4">
                <h2 class="mb-1 text-3xl font-bold">Write your new post!</h2>

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
</div>
