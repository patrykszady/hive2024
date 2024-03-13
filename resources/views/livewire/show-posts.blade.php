<div class="flex flex-col gap-8 min-w-[40rem]">
    <h1 class="text-3xl font-semibold leading-6 text-slate-900">Blog Posts</h1>

    <div class="overflow-hidden bg-white shadow rounded-xl">
        <table class="min-w-full divide-y divide-slate-300">
            <thead class="py-2 bg-gray-50">
                <tr class="font-semibold text-left text-slate-800">
                    <th class="py-4 pl-6">Title</th>
                    <th class="py-4 pl-4">Content</th>
                    <th class="pl-4 pr-4">
                        <livewire:add-post-dialog @added="$refresh" />
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200" wire:loading.class="opacity-50">
                @foreach ($posts as $post)
                    <livewire:post-row :key="$post->id" :$post @deleted="delete({{ $post->id }})" />
                @endforeach
            </tbody>
        </table>
    </div>
</div>
