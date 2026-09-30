@props([
    'title',
    'subtitle',
    'image',
    'pos' => 'pos-2',
])

<div class="stacked-card {{ $pos }}" style="background-image: url('{{ asset($image) }}');">
    <div class="card-content">
        <div class="card-title">{{ $title }}</div>
        <div class="card-subtitle">{{ $subtitle }}</div>
    </div>
</div>
