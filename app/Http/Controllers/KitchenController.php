<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitchenController extends Controller
{
    /**
     * Afficher l'interface cuisine
     */
    public function index()
    {
        // Commandes en attente et en préparation
        $pendingOrders = DB::table('orders')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $preparingOrders = DB::table('orders')
            ->where('status', 'preparing')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $readyOrders = DB::table('orders')
            ->where('status', 'ready')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Statistiques cuisine
        $stats = [
            'pending' => $pendingOrders->count(),
            'preparing' => $preparingOrders->count(),
            'ready' => $readyOrders->count(),
            'total_today' => DB::table('orders')->whereDate('created_at', today())->count(),
        ];
        
        return view('kitchen.dashboard', compact('pendingOrders', 'preparingOrders', 'readyOrders', 'stats'));
    }
    
    /**
     * Afficher les détails d'une commande pour la cuisine
     */
    public function showOrder($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        
        if (!$order) {
            return redirect()->route('kitchen.dashboard')->with('error', 'Commande non trouvée');
        }
        
        $items = DB::table('order_items')
            ->join('dishes', 'order_items.dish_id', '=', 'dishes.id')
            ->select('order_items.*', 'dishes.name as dish_name')
            ->where('order_items.order_id', $id)
            ->get();
        
        return view('kitchen.order-details', compact('order', 'items'));
    }
    
    /**
     * Changer le statut d'une commande (depuis la cuisine)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled'
        ]);
        
        DB::table('orders')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);
        
        // Si la requête est AJAX, retourner JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour',
                'status' => $request->status
            ]);
        }
        
        return redirect()->back()->with('success', 'Statut de la commande mis à jour');
    }
    
    /**
     * API: Récupérer les commandes pour rafraîchissement automatique
     */
    public function getOrdersApi()
    {
        $pendingOrders = DB::table('orders')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $preparingOrders = DB::table('orders')
            ->where('status', 'preparing')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $readyOrders = DB::table('orders')
            ->where('status', 'ready')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'pending' => $pendingOrders,
            'preparing' => $preparingOrders,
            'ready' => $readyOrders,
        ]);
    }
    
    /**
     * API: Récupérer les détails d'une commande
     * Pour le rafraîchissement automatique dans l'interface cuisine
     */
    public function getOrderDetailsApi($id)
    {
        // Récupérer la commande
        $order = DB::table('orders')->where('id', $id)->first();
        
        if (!$order) {
            return response()->json([
                'success' => false,
                'error' => 'Commande non trouvée'
            ], 404);
        }
        
        // Récupérer les articles de la commande avec les noms des plats
        $items = DB::table('order_items')
            ->join('dishes', 'order_items.dish_id', '=', 'dishes.id')
            ->select(
                'order_items.id',
                'order_items.order_id',
                'order_items.dish_id',
                'order_items.quantity',
                'order_items.price',
                'dishes.name as dish_name'
            )
            ->where('order_items.order_id', $id)
            ->get();
        
        // Retourner les données au format JSON
        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'order_type' => $order->order_type,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at
            ],
            'items' => $items,
            'total' => (float) $order->total_amount
        ]);
    }
}