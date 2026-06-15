<?php

namespace Database\Seeders;

use App\Models\Commande;
use App\Models\CommandeItem;
use App\Models\Livraison;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommandeSeeder extends Seeder
{
    public function run(): void
    {
        $youssef = User::where('email', 'youssef.benali@tissu.ma')->firstOrFail();
        $leila = User::where('email', 'leila.mansouri@tissu.ma')->firstOrFail();
        $karim = User::where('email', 'karim.alami@tissu.ma')->firstOrFail();

        $beniOuarain = Product::where('slug', 'beni-ouarain-premium')->firstOrFail();
        $broderieFassi = Product::where('slug', 'broderie-fassi-sur-soie')->firstOrFail();
        $echarpeSahara = Product::where('slug', 'echarpe-sahara')->firstOrFail();
        $djellabaRbatie = Product::where('slug', 'djellaba-rbatie-brodee')->firstOrFail();
        $kilimChefchaouen = Product::where('slug', 'kilim-chefchaouen')->firstOrFail();
        $tajinePeint = Product::where('slug', 'tajine-peint-main')->firstOrFail();

        $orders = [
            39 => [
                'user_id' => $youssef->id,
                'status' => 'annulee',
                'shipping_address' => '12 Rue des Consuls, Médina',
                'shipping_city' => 'Rabat',
                'shipping_postal_code' => '10000',
                'payment_method' => 'carte',
                'notes' => 'Commande annulée par le client avant expédition.',
                'items' => [
                    ['product' => $beniOuarain, 'quantity' => 1],
                ],
                'livraison' => [
                    'status' => 'en_attente',
                    'tracking_number' => null,
                    'carrier' => null,
                    'shipped_at' => null,
                    'delivered_at' => null,
                ],
            ],
            40 => [
                'user_id' => $leila->id,
                'status' => 'en_attente',
                'shipping_address' => '45 Boulevard Zerktouni',
                'shipping_city' => 'Casablanca',
                'shipping_postal_code' => '20000',
                'payment_method' => 'livraison',
                'notes' => null,
                'items' => [
                    ['product' => $echarpeSahara, 'quantity' => 2],
                    ['product' => $broderieFassi, 'quantity' => 1],
                ],
                'livraison' => [
                    'status' => 'en_attente',
                    'tracking_number' => null,
                    'carrier' => null,
                    'shipped_at' => null,
                    'delivered_at' => null,
                ],
            ],
            41 => [
                'user_id' => $youssef->id,
                'status' => 'livree',
                'shipping_address' => '8 Avenue Mohammed V',
                'shipping_city' => 'Rabat',
                'shipping_postal_code' => '10030',
                'payment_method' => 'virement',
                'notes' => 'Livraison effectuée avec succès.',
                'items' => [
                    ['product' => $djellabaRbatie, 'quantity' => 1],
                ],
                'livraison' => [
                    'status' => 'livree',
                    'tracking_number' => 'TA-2024-0041-MA',
                    'carrier' => 'Amana',
                    'shipped_at' => now()->subDays(5),
                    'delivered_at' => now()->subDays(2),
                ],
            ],
            42 => [
                'user_id' => $karim->id,
                'status' => 'confirmee',
                'shipping_address' => '3 Rue de la Kasbah',
                'shipping_city' => 'Tanger',
                'shipping_postal_code' => '90000',
                'payment_method' => 'carte',
                'notes' => 'Commande confirmée, en attente de préparation.',
                'items' => [
                    ['product' => $kilimChefchaouen, 'quantity' => 1],
                    ['product' => $tajinePeint, 'quantity' => 2],
                ],
                'livraison' => [
                    'status' => 'en_cours',
                    'tracking_number' => 'TA-2024-0042-MA',
                    'carrier' => 'CTM Express',
                    'shipped_at' => now()->subDay(),
                    'delivered_at' => null,
                ],
            ],
        ];

        foreach ($orders as $id => $orderData) {
            $totalHt = collect($orderData['items'])->sum(function (array $item) {
                return $item['product']->price * $item['quantity'];
            });
            $tva = round($totalHt * 0.20, 2);
            $totalTtc = round($totalHt + $tva, 2);

            $commande = Commande::updateOrCreate(
                ['id' => $id],
                [
                    'user_id' => $orderData['user_id'],
                    'status' => $orderData['status'],
                    'total_ht' => $totalHt,
                    'tva' => $tva,
                    'total_ttc' => $totalTtc,
                    'shipping_address' => $orderData['shipping_address'],
                    'shipping_city' => $orderData['shipping_city'],
                    'shipping_postal_code' => $orderData['shipping_postal_code'],
                    'payment_method' => $orderData['payment_method'],
                    'notes' => $orderData['notes'],
                ]
            );

            $commande->items()->delete();

            foreach ($orderData['items'] as $item) {
                /** @var Product $product */
                $product = $item['product'];
                $quantity = $item['quantity'];
                $unitPrice = $product->price;
                $subtotal = round($unitPrice * $quantity, 2);

                CommandeItem::create([
                    'commande_id' => $commande->id,
                    'product_id' => $product->id,
                    'artisan_id' => $product->artisan_id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
            }

            Livraison::updateOrCreate(
                ['commande_id' => $commande->id],
                $orderData['livraison']
            );
        }

        $this->syncCommandesSequence();
    }

    private function syncCommandesSequence(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('commandes', 'id'), (SELECT COALESCE(MAX(id), 1) FROM commandes))");
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE commandes AUTO_INCREMENT = '.(Commande::max('id') + 1));
        }
    }
}
