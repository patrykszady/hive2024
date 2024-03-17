<div class="flex flex-col gap-8 min-w-[40rem]">
    <h1 class="text-3xl font-semibold leading-6 text-slate-900">Blog Posts</h1>

    <div class="overflow-hidden bg-white shadow rounded-xl">
        <table class="min-w-full divide-y divide-slate-300">
            <thead class="py-2 bg-slate-50">
                <tr class="font-semibold text-left text-slate-800">
                    <th class="py-4 pl-6">Title</th>
                    <th class="py-4 pl-4">Content</th>
                    <th class="pl-4"></th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200" wire:loading.class="opacity-50">
                @foreach ($posts as $post)
                    <tr class="text-left text-slate-900">
                        <td class="py-4 pl-6 pr-3 font-medium">{{ $post->title }}</td>
                        <td class="py-4 pl-4 text-left text-slate-500">{{ str($post->content)->limit(50) }}</td>
                        <td class="py-4 pl-4 pr-6 text-right">
                            <x-dialog>
                                <x-dialog.button>
                                    <button type="button" class="font-medium text-red-600">
                                        Delete
                                    </button>
                                </x-dialog.button>

                                <x-dialog.panel>
                                    <div class="flex flex-col gap-6" x-data="{ confirmation: '' }">
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

                                            <x-dialog.close-button>
                                                <button :disabled="confirmation !== 'CONFIRM'" wire:click="delete({{ $post->id }})" class="px-6 py-2 text-lg font-semibold text-center text-white bg-red-500 rounded-xl disabled:cursor-not-allowed disabled:opacity-50">Delete</button>
                                            </x-dialog.close-button>
                                        </x-dialog.footer>
                                    </div>
                                </x-dialog.panel>
                            </x-dialog>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
