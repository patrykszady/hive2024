@props([
    'handler',
    'group'
    ])

<div
    {{$attributes}}
    x-sort="$wire.{{$handler}}($item, $position)"
    @if($group) x-sort:group="{{$group}}" @endif
    >

    {{$slot}}
</div>
