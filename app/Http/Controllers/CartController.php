<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Afficher le panier
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart', compact('cart', 'total'));
    }
    
    /**
     * Ajouter un plat au panier
     */
    public function add(Request $request, $id)
    {
        // Récupérer le plat depuis la base de données
        $dish = DB::table('dishes')->where('id', $id)->first();
        
        if (!$dish) {
            return redirect()->back()->with('error', 'Plat non trouvé');
        }
        
        // Récupérer le panier existant ou créer un nouveau
        $cart = session()->get('cart', []);
        
        // Si le plat est déjà dans le panier, augmenter la quantité
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Sinon, ajouter le plat au panier
            $cart[$id] = [
                'name' => $dish->name,
                'price' => $dish->price,
                'quantity' => 1,
                'image_url' => $dish->image_url ?? null
            ];
        }
        
        // Sauvegarder le panier dans la session
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', '✅ ' . $dish->name . ' ajouté au panier !');
    }
    
    /**
     * Supprimer un plat du panier
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $dishName = $cart[$id]['name'];
            unset($cart[$id]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', '❌ ' . $dishName . ' retiré du panier');
        }
        
        return redirect()->back()->with('error', 'Plat non trouvé dans le panier');
    }
    
    /**
     * Mettre à jour la quantité d'un plat
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $quantity = $request->input('quantity', 1);
            
            if ($quantity <= 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $quantity;
            }
            
            session()->put('cart', $cart);
        }
        
        return redirect()->back();
    }
}
