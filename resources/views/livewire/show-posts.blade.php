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
                        <td class="py-4 pl-6 pr-3 font-medium">{{ $post->date->format('Y-m-d') }}</td>
                        {{-- str($post->content)->limit(50) --}}
                        <td class="py-4 pl-4 text-left text-slate-500">{{ $post->amount }}</td>
                        <td class="py-4 pl-4 pr-6 text-right">
                            <button wire:click="delete({{$post->id}})" type="button" class="font-medium text-red-600">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
