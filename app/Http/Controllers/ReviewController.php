<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Afficher la page des avis pour un plat
     */
    public function show($dishId)
    {
        $dish = DB::table('dishes')->where('id', $dishId)->first();
        
        if (!$dish) {
            return redirect('/menu-client')->with('error', 'Plat non trouvé');
        }
        
        $category = DB::table('categories')->where('id', $dish->category_id)->first();
        
        $reviews = DB::table('reviews')
            ->where('dish_id', $dishId)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $ratingDistribution = [
            5 => DB::table('reviews')->where('dish_id', $dishId)->where('rating', 5)->count(),
            4 => DB::table('reviews')->where('dish_id', $dishId)->where('rating', 4)->count(),
            3 => DB::table('reviews')->where('dish_id', $dishId)->where('rating', 3)->count(),
            2 => DB::table('reviews')->where('dish_id', $dishId)->where('rating', 2)->count(),
            1 => DB::table('reviews')->where('dish_id', $dishId)->where('rating', 1)->count(),
        ];
        
        return view('reviews.show', compact('dish', 'category', 'reviews', 'ratingDistribution'));
    }
    
    /**
     * Ajouter un avis
     */
    public function store(Request $request, $dishId)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000'
        ]);
        
        // Insérer l'avis
        DB::table('reviews')->insert([
            'dish_id' => $dishId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // À approuver par admin
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Mettre à jour la moyenne des notes
        $this->updateDishRating($dishId);
        
        return redirect()->back()->with('success', 'Merci pour votre avis ! Il sera affiché après validation.');
    }
    
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
}