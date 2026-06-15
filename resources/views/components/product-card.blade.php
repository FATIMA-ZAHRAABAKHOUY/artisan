@props(['product'])

<div {{ $attributes->merge(['class' => 'card card-tissu h-100']) }}>
    <a href="{{ route('catalogue.show', $product) }}" class="text-decoration-none">
        @if ($product->main_image)
            <img src="{{ asset('storage/' . $product->main_image) }}" class="card-img-top" alt="{{ $product->name }}">
        @else
            <div class="card-img-top d-flex align-items-center justify-content-center bg-sable" style="background: #E8D9BB; min-height: 180px;">
                <span class="fs-1">{{ $product->getEmojiFallback() }}</span>
            </div>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        @if ($product->category)
            <small class="text-muted mb-1">{{ $product->category->icon }} {{ $product->category->name }}</small>
        @endif
        <h5 class="card-title font-serif mb-1">
            <a href="{{ route('catalogue.show', $product) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
        </h5>
        @if ($product->artisan)
            <small class="text-muted mb-2">
                Par {{ $product->artisan->user->name ?? 'Artisan' }}
            </small>
        @endif
        @if ($product->review_count > 0)
            <div class="mb-2">
                <x-star-rating :rating="$product->average_rating" />
                <small class="text-muted">({{ $product->review_count }})</small>
            </div>
        @endif
        <div class="price-display mt-auto mb-2">{{ mad_format($product->price) }}</div>
        <div class="d-flex justify-content-between align-items-center">
            @if ($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="d-inline ms-auto">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-sm btn-or">🛒 Ajouter</button>
                </form>
            @else
                <small class="stock-badge stock-out">Rupture</small>
            @endif
        </div>
    </div>
</div>
