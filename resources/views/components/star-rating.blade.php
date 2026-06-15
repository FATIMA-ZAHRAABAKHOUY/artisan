@props(['rating' => 0, 'max' => 5, 'size' => 'sm'])

@php
    $rating = (float) $rating;
    $max = (int) $max;
    $sizeClass = match ($size) {
        'lg' => 'star-lg',
        'md' => '',
        default => 'star-sm',
    };
@endphp

<div {{ $attributes->merge(['class' => 'star-rating d-inline-flex gap-1 ' . $sizeClass]) }} aria-label="{{ $rating }} sur {{ $max }} étoiles">
    @for ($i = 1; $i <= $max; $i++)
        @php
            $filled = $rating >= $i;
            $half = ! $filled && $rating >= ($i - 0.5);
        @endphp
        <span class="star {{ $filled || $half ? 'filled' : '' }}">{!! $filled || $half ? '&#9733;' : '&#9734;' !!}</span>
    @endfor
</div>
