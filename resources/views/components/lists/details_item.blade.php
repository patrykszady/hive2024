@props([
    'title' => null,
    'detail' => null,
    'href' => null,
    'target' => null
])

<div class="py-3 grid grid-cols-3 gap-4">
    <dt class="text-sm font-medium text-gray-900">{{$title}}</dt>
    <dd class="text-sm text-gray-700 col-start-2 col-span-2">
        @if($href)
            <a href="{{$href}}"
                @if($target)
                    target="{{$target}}"
                @else
                    wire:navigate.hover
                @endif
                >
                {!!$detail!!}
            </a>
        @else
            {!!$detail!!}
        @endif
    </dd>
</div>
