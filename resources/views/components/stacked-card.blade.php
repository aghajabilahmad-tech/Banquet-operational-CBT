@props([
    'title',
    'subtitle',
    'image',
    'pos' => 'pos-1',
])

@php
    $imageUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
        ? $image
        : asset($image);
@endphp

<div class="stacked-card {{ $pos }}" style="background-image: url('{{ $imageUrl }}');">
    <div class="card-content">
        <div class="card-title">{{ $title }}</div>
        <div class="card-subtitle">{{ $subtitle }}</div>
    </div>
</div>
