<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Afficher la page de validation de commande
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect('/menu')->with('error', 'Votre panier est vide');
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('checkout', compact('cart', 'total'));
    }
    
    /**
     * Enregistrer la commande dans la base de données
     */
    public function store(Request $request)
    {
        // 1. Valider les données du formulaire
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'order_type' => 'required|in:dine_in,takeaway'
        ]);
        
        // 2. Récupérer le panier
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect('/menu')->with('error', 'Votre panier est vide');
        }
        
        // 3. Calculer le total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // 4. Générer un numéro de commande unique
        $orderNumber = 'ORD-' . strtoupper(uniqid());
        
        // 5. Insérer la commande dans la table orders
        $orderId = DB::table('orders')->insertGetId([
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'total_amount' => $total,
            'order_type' => $request->order_type,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // 6. Insérer les détails de la commande dans order_items
        foreach ($cart as $dishId => $item) {
            DB::table('order_items')->insert([
                'order_id' => $orderId,
                'dish_id' => $dishId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // 7. Vider le panier
        session()->forget('cart');
        
        // 8. Rediriger avec message de succès
        return redirect('/')->with('success', '✅ Commande ' . $orderNumber . ' enregistrée avec succès ! Nous vous contacterons sous peu.');
    }
}