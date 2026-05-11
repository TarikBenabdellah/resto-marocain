<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Désactiver les contraintes
        Schema::disableForeignKeyConstraints();
        
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('reviews')->truncate();
        DB::table('dishes')->truncate();
        DB::table('categories')->truncate();
        
        Schema::enableForeignKeyConstraints();
        
        // Catégories
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Marocain', 'display_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Fast Food', 'display_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Desserts', 'display_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Boissons', 'display_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Plats
        DB::table('dishes')->insert([
            ['id' => 1, 'category_id' => 1, 'name' => 'Cigan Fruit de Mer', 'description' => '6 pièces de cigares aux fruits de mer', 'price' => 59.00, 'is_recommended' => true, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'category_id' => 1, 'name' => 'Déo Salée', 'description' => 'Pastilla fruit de mer, cigar fromage, briwat poulet', 'price' => 57.00, 'is_recommended' => true, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'category_id' => 1, 'name' => 'Menu Harira et Salée', 'description' => 'Pastilla fruit de mer + briwat', 'price' => 45.00, 'is_recommended' => false, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'category_id' => 1, 'name' => 'Tajine Kefta', 'description' => 'Tajine traditionnel aux kefta et œufs', 'price' => 65.00, 'is_recommended' => true, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'category_id' => 1, 'name' => 'Couscous Royal', 'description' => 'Couscous aux 7 légumes et viande', 'price' => 85.00, 'is_recommended' => true, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}