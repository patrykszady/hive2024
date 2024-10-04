@props([
    'title' => null,
    'detail' => null,
    'href' => null,
])

<div class="py-3 grid grid-cols-2 gap-4">
    <dt class="text-sm font-medium text-gray-900">{{$title}}</dt>
    <dd class="text-sm text-gray-700">
        @if($href)
            <a wire:navigate.hover href="{{$href}}">
                {{$detail}}
            </a>
        @else
            {{$detail}}
        @endif
    </dd>
</div>
