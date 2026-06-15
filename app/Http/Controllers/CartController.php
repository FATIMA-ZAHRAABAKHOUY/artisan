<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show()
    {
        $cart = session('cart', []);
        $items = $this->resolveCartItems($cart);

        $subtotal = $items->sum(fn (array $item) => $item['subtotal']);

        return view('cart.show', compact('items', 'subtotal'));
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::query()
            ->publicCatalogue()
            ->findOrFail($validated['product_id']);

        if ($product->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stock insuffisant pour ce produit.']);
        }

        $cart = session('cart', []);
        $productId = (string) $product->id;
        $currentQuantity = $cart[$productId] ?? 0;
        $newQuantity = $currentQuantity + $validated['quantity'];

        if ($newQuantity > $product->stock) {
            return back()->withErrors(['quantity' => 'La quantité demandée dépasse le stock disponible.']);
        }

        $cart[$productId] = $newQuantity;
        session(['cart' => $cart]);

        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::query()
            ->publicCatalogue()
            ->findOrFail($validated['product_id']);

        if ($validated['quantity'] > $product->stock) {
            return back()->withErrors(['quantity' => 'La quantité demandée dépasse le stock disponible.']);
        }

        $cart = session('cart', []);
        $cart[(string) $product->id] = $validated['quantity'];
        session(['cart' => $cart]);

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $cart = session('cart', []);
        unset($cart[(string) $validated['product_id']]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produit retiré du panier.');
    }

    private function resolveCartItems(array $cart)
    {
        if (empty($cart)) {
            return collect();
        }

        $products = Product::query()
            ->publicCatalogue()
            ->whereIn('id', array_keys($cart))
            ->with(['artisan.user', 'category'])
            ->get()
            ->keyBy('id');

        return collect($cart)
            ->map(function (int $quantity, string $productId) use ($products) {
                $product = $products->get((int) $productId);

                if (! $product) {
                    return null;
                }

                $effectiveQuantity = min($quantity, $product->stock);

                return [
                    'product' => $product,
                    'quantity' => $effectiveQuantity,
                    'unit_price' => $product->price,
                    'subtotal' => $product->price * $effectiveQuantity,
                ];
            })
            ->filter()
            ->values();
    }
}
