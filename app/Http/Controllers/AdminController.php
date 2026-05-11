<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Afficher le dashboard admin avec graphiques
     */
    public function index()
    {
        // ==========================================
        // STATISTIQUES DE BASE
        // ==========================================
        $totalOrders = DB::table('orders')->count();
        $totalRevenue = DB::table('orders')->sum('total_amount');
        $pendingOrders = DB::table('orders')->where('status', 'pending')->count();
        $todayOrders = DB::table('orders')->whereDate('created_at', today())->count();
        
        // Commandes récentes
        $recentOrders = DB::table('orders')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // ==========================================
        // DONNÉES POUR LES GRAPHIQUES
        // ==========================================
        
        // 1. Ventes par mois (6 derniers mois) - VERSION CORRIGÉE
        $monthlySales = DB::table('orders')
            ->select(DB::raw('DATE_FORMAT(created_at, "%M") as month'), DB::raw('MONTH(created_at) as month_num'), DB::raw('SUM(total_amount) as total'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('DATE_FORMAT(created_at, "%M")'))
            ->orderBy('month_num', 'asc')
            ->get();
        
        $months = [];
        $salesData = [];
        
        foreach ($monthlySales as $sale) {
            $months[] = $sale->month;
            $salesData[] = (float) $sale->total;
        }
        
        // 2. Commandes par statut (pour le camembert)
        $statusCounts = [
            'pending' => DB::table('orders')->where('status', 'pending')->count(),
            'preparing' => DB::table('orders')->where('status', 'preparing')->count(),
            'ready' => DB::table('orders')->where('status', 'ready')->count(),
            'completed' => DB::table('orders')->where('status', 'completed')->count(),
            'cancelled' => DB::table('orders')->where('status', 'cancelled')->count(),
        ];
        
        // 3. Plats les plus vendus (Top 5)
        $topDishes = DB::table('order_items')
            ->join('dishes', 'order_items.dish_id', '=', 'dishes.id')
            ->select('dishes.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('dishes.id', 'dishes.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
        
        $dishNames = [];
        $dishQuantities = [];
        
        foreach ($topDishes as $dish) {
            $dishNames[] = $dish->name;
            $dishQuantities[] = (int) $dish->total_quantity;
        }
        
        // 4. Commandes par jour de la semaine
        $weekDays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $dailyOrders = [];
        
        foreach (range(0, 6) as $day) {
            $dailyOrders[] = DB::table('orders')
                ->whereRaw('WEEKDAY(created_at) = ?', [$day])
                ->count();
        }
        
        // Statistiques pour les cartes
        $stats = [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'pending_orders' => $pendingOrders,
            'today_orders' => $todayOrders,
        ];
        
        // Passer toutes les données à la vue
        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'pendingOrders', 'todayOrders', 
            'recentOrders', 'stats', 'months', 'salesData', 'statusCounts',
            'dishNames', 'dishQuantities', 'dailyOrders', 'weekDays'
        ));
    }
    
    /**
     * Afficher la liste des commandes
     */
    public function orders()
    {
        $orders = DB::table('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.orders', compact('orders'));
    }
    
    /**
     * Afficher les détails d'une commande
     */
    public function showOrder($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();
        
        if (!$order) {
            return redirect()->route('admin.orders')->with('error', 'Commande non trouvée');
        }
        
        $items = DB::table('order_items')
            ->join('dishes', 'order_items.dish_id', '=', 'dishes.id')
            ->select('order_items.*', 'dishes.name as dish_name')
            ->where('order_items.order_id', $id)
            ->get();
        
        return view('admin.order-details', compact('order', 'items'));
    }
    
    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateOrderStatus(Request $request, $id)
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
        
        return redirect()->back()->with('success', 'Statut de la commande mis à jour');
    }
    
    /**
     * Afficher la liste des plats
     */
    public function dishes()
    {
        $dishes = DB::table('dishes')
            ->join('categories', 'dishes.category_id', '=', 'categories.id')
            ->select('dishes.*', 'categories.name as category_name')
            ->orderBy('dishes.created_at', 'desc')
            ->paginate(15);
        
        $categories = DB::table('categories')->get();
        
        return view('admin.dishes', compact('dishes', 'categories'));
    }
    
    /**
     * Ajouter un plat avec image
     */
    public function addDish(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_recommended' => 'boolean',
            'is_available' => 'boolean'
        ]);
        
        $imageUrl = null;
        
        // Traitement de l'upload d'image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/dishes');
            
            // Créer le dossier s'il n'existe pas
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $file->move($destinationPath, $filename);
            $imageUrl = '/images/dishes/' . $filename;
        }
        
        DB::table('dishes')->insert([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $imageUrl,
            'is_recommended' => $request->has('is_recommended') ? 1 : 0,
            'is_available' => $request->has('is_available') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('admin.dishes')->with('success', 'Plat ajouté avec succès');
    }
    
    /**
     * Modifier un plat avec image
     */
    public function editDish(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_recommended' => 'boolean',
            'is_available' => 'boolean'
        ]);
        
        $dish = DB::table('dishes')->where('id', $id)->first();
        $imageUrl = $dish->image_url;
        
        // Traitement de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($imageUrl && file_exists(public_path($imageUrl))) {
                unlink(public_path($imageUrl));
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/dishes');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $file->move($destinationPath, $filename);
            $imageUrl = '/images/dishes/' . $filename;
        }
        
        DB::table('dishes')
            ->where('id', $id)
            ->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image_url' => $imageUrl,
                'is_recommended' => $request->has('is_recommended') ? 1 : 0,
                'is_available' => $request->has('is_available') ? 1 : 0,
                'updated_at' => now()
            ]);
        
        return redirect()->route('admin.dishes')->with('success', 'Plat modifié avec succès');
    }
    
    /**
     * Supprimer un plat
     */
    public function deleteDish($id)
    {
        // Vérifier si le plat est utilisé dans des commandes
        $usedInOrders = DB::table('order_items')->where('dish_id', $id)->exists();
        
        if ($usedInOrders) {
            return redirect()->back()->with('error', 'Ce plat ne peut pas être supprimé car il est utilisé dans des commandes');
        }
        
        DB::table('dishes')->where('id', $id)->delete();
        
        return redirect()->route('admin.dishes')->with('success', 'Plat supprimé avec succès');
    }
    
    // ==========================================
    // GESTION DES AVIS
    // ==========================================
    
    /**
     * Mettre à jour la moyenne des notes d'un plat
     */
    private function updateDishRating($dishId)
    {
        $approvedReviews = DB::table('reviews')
            ->where('dish_id', $dishId)
            ->where('is_approved', true);
        
        $avgRating = $approvedReviews->avg('rating');
        $count = $approvedReviews->count();
        
        DB::table('dishes')
            ->where('id', $dishId)
            ->update([
                'rating_avg' => $avgRating ?? 0,
                'rating_count' => $count,
                'updated_at' => now()
            ]);
    }
    
    /**
     * Afficher la liste des avis à modérer
     */
    public function reviews()
    {
        $pendingReviews = DB::table('reviews')
            ->join('dishes', 'reviews.dish_id', '=', 'dishes.id')
            ->select('reviews.*', 'dishes.name as dish_name')
            ->where('reviews.is_approved', false)
            ->orderBy('reviews.created_at', 'desc')
            ->get();
        
        $approvedReviews = DB::table('reviews')
            ->join('dishes', 'reviews.dish_id', '=', 'dishes.id')
            ->select('reviews.*', 'dishes.name as dish_name')
            ->where('reviews.is_approved', true)
            ->orderBy('reviews.created_at', 'desc')
            ->get();
        
        return view('admin.reviews', compact('pendingReviews', 'approvedReviews'));
    }
    
    /**
     * Approuver un avis
     */
    public function approveReview($id)
    {
        DB::table('reviews')
            ->where('id', $id)
            ->update([
                'is_approved' => true,
                'updated_at' => now()
            ]);
        
        // Récupérer le dish_id pour mettre à jour la moyenne
        $review = DB::table('reviews')->where('id', $id)->first();
        $this->updateDishRating($review->dish_id);
        
        return redirect()->back()->with('success', 'Avis approuvé avec succès');
    }
    
    /**
     * Supprimer un avis
     */
    public function deleteReview($id)
    {
        $review = DB::table('reviews')->where('id', $id)->first();
        $dishId = $review->dish_id;
        
        DB::table('reviews')->where('id', $id)->delete();
        
        // Mettre à jour la moyenne
        $this->updateDishRating($dishId);
        
        return redirect()->back()->with('success', 'Avis supprimé avec succès');
    }
}