<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommandeRequest;
use App\Models\Commande;
use App\Models\CommandeItem;
use App\Models\Livraison;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    public function checkout()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.show')->withErrors(['cart' => 'Votre panier est vide.']);
        }

        $items = $this->resolveCartItems($cart);

        if ($items->isEmpty()) {
            return redirect()->route('cart.show')->withErrors(['cart' => 'Aucun produit valide dans votre panier.']);
        }

        $totalHt = $items->sum(fn (array $item) => $item['subtotal']);
        $tva = round($totalHt * 0.20, 2);
        $totalTtc = round($totalHt + $tva, 2);

        return view('commandes.checkout', compact('items', 'totalHt', 'tva', 'totalTtc'));
    }

    public function store(StoreCommandeRequest $request): RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.show')->withErrors(['cart' => 'Votre panier est vide.']);
        }

        $items = $this->resolveCartItems($cart);

        if ($items->isEmpty()) {
            return redirect()->route('cart.show')->withErrors(['cart' => 'Aucun produit valide dans votre panier.']);
        }

        try {
            $commande = DB::transaction(function () use ($request, $items) {
                $totalHt = round($items->sum(fn (array $item) => $item['subtotal']), 2);
                $tva = round($totalHt * 0.20, 2);
                $totalTtc = round($totalHt + $tva, 2);

                $commande = Commande::create([
                    'user_id' => $request->user()->id,
                    'status' => 'en_attente',
                    'total_ht' => $totalHt,
                    'tva' => $tva,
                    'total_ttc' => $totalTtc,
                    'shipping_address' => $request->validated('shipping_address'),
                    'shipping_city' => $request->validated('shipping_city'),
                    'shipping_postal_code' => $request->validated('shipping_postal_code'),
                    'payment_method' => $request->validated('payment_method'),
                    'notes' => $request->validated('notes'),
                ]);

                foreach ($items as $item) {
                    $product = Product::query()->lockForUpdate()->find($item['product']->id);

                    if (! $product || $product->stock < $item['quantity']) {
                        throw new \RuntimeException('Stock insuffisant pour '.$item['product']->name);
                    }

                    CommandeItem::create([
                        'commande_id' => $commande->id,
                        'product_id' => $product->id,
                        'artisan_id' => $product->artisan_id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $product->decrement('stock', $item['quantity']);
                }

                Livraison::create([
                    'commande_id' => $commande->id,
                    'status' => 'en_attente',
                ]);

                return $commande;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors(['cart' => $e->getMessage()]);
        }

        session()->forget('cart');

        return redirect()
            ->route('commandes.confirmation', $commande)
            ->with('success', 'Commande passée avec succès.');
    }

    public function confirmation(Commande $commande)
    {
        $this->authorizeCommande($commande);

        $commande->load(['items.product', 'items.artisan.user', 'livraison']);

        return view('commandes.confirmation', compact('commande'));
    }

    public function index()
    {
        $commandes = auth()->user()
            ->commandes()
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('commandes.index', compact('commandes'));
    }

    public function show(Commande $commande)
    {
        $this->authorizeCommande($commande);

        $commande->load(['items.product', 'items.artisan.user', 'livraison']);

        return view('commandes.show', compact('commande'));
    }

    private function authorizeCommande(Commande $commande): void
    {
        if ($commande->user_id !== auth()->id()) {
            abort(403);
        }
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

                if (! $product || $product->stock < 1) {
                    return null;
                }

                $effectiveQuantity = min($quantity, $product->stock);

                return [
                    'product' => $product,
                    'quantity' => $effectiveQuantity,
                    'unit_price' => $product->price,
                    'subtotal' => round($product->price * $effectiveQuantity, 2),
                ];
            })
            ->filter()
            ->values();
    }
}
