<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resto Marocain - Notre Menu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* En-tête */
        h1 {
            text-align: center;
            color: #e67e22;
            margin-bottom: 10px;
            font-size: 2.5rem;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 5px;
        }
        
        .resto-name {
            text-align: center;
            color: #e67e22;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        /* Panier et navigation */
        .cart-link {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .cart-link a {
            background: #e67e22;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }
        
        .cart-link a:hover {
            background: #d35400;
        }
        
        /* Messages de succès et d'erreur */
        .alert-success {
            background: #27ae60;
            color: white;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            animation: fadeOut 3s ease-in-out forwards;
        }
        
        .alert-error {
            background: #e74c3c;
            color: white;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            animation: fadeOut 3s ease-in-out forwards;
        }
        
        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; visibility: hidden; }
        }
        
        /* ========== BARRE DE RECHERCHE ========== */
        .search-container {
            max-width: 500px;
            margin: 0 auto 25px auto;
            position: relative;
        }
        
        #searchInput {
            width: 100%;
            padding: 14px 20px;
            font-size: 1rem;
            border: 2px solid #e67e22;
            border-radius: 50px;
            outline: none;
            transition: all 0.3s;
            background: white;
        }
        
        #searchInput:focus {
            border-color: #d35400;
            box-shadow: 0 0 10px rgba(230, 126, 34, 0.3);
        }
        
        .search-count {
            text-align: center;
            margin-top: 8px;
            font-size: 0.85rem;
            color: #666;
        }
        
        .search-count strong {
            color: #e67e22;
        }
        
        /* Catégories */
        .categories {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        
        .category-btn {
            padding: 10px 25px;
            background: white;
            border: 2px solid #e67e22;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            color: #e67e22;
        }
        
        .category-btn.active {
            background: #e67e22;
            color: white;
        }
        
        .category-btn:hover {
            background: #e67e22;
            color: white;
        }
        
        /* Grille des plats */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .menu-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
        }
        
        /* Image du plat */
        .dish-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        /* Contenu de la carte */
        .card-content {
            padding: 20px;
        }
        
        .dish-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .dish-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .dish-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #e67e22;
            margin-bottom: 10px;
        }
        
        .recommended {
            display: inline-block;
            background: #27ae60;
            color: white;
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 20px;
            margin-left: 10px;
            vertical-align: middle;
        }
        
        /* ========== NOTES ET AVIS ========== */
        .rating {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin: 10px 0;
        }
        
        .stars {
            display: flex;
            gap: 2px;
        }
        
        .star {
            font-size: 1rem;
        }
        
        .star.filled {
            color: #f39c12;
        }
        
        .star {
            color: #ddd;
        }
        
        .rating-value {
            font-size: 0.8rem;
            color: #666;
        }
        
        .review-link {
            font-size: 0.75rem;
            color: #e67e22;
            text-decoration: none;
        }
        
        .review-link:hover {
            text-decoration: underline;
        }
        
        /* Bouton ajouter au panier */
        .add-to-cart {
            background: #e67e22;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background 0.3s;
            margin-top: 10px;
        }
        
        .add-to-cart:hover {
            background: #d35400;
        }
        
        form {
            display: inline;
        }
        
        .empty {
            text-align: center;
            color: #999;
            padding: 50px;
        }
        
        footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            color: #999;
            font-size: 0.8rem;
        }
        
        .no-results {
            text-align: center;
            padding: 50px;
            color: #999;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- ========================================== -->
        <!-- BARRE DE NAVIGATION AVEC BOUTON ACCUEIL      -->
        <!-- ========================================== -->
        <div class="cart-link">
            <a href="/" style="background: #2c3e50; margin-right: 10px;">
                🏠 Accueil
            </a>
            <a href="{{ route('cart.index') }}" style="margin-right: 10px;">
                🛒 Panier ({{ session()->get('cart') ? array_sum(array_column(session()->get('cart'), 'quantity')) : 0 }})
            </a>
            <a href="/admin" style="background: #2c3e50;">
                👑 Admin
            </a>
        </div>
        
        <h1>🍽️ Notre Menu</h1>
        <div class="subtitle">Table 27 - TERRASSE</div>
        <div class="resto-name">Resto Marocain Casa</div>
        
        <!-- ========================================== -->
        <!-- MESSAGES DE SUCCÈS ET D'ERREUR             -->
        <!-- ========================================== -->
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- ========================================== -->
        <!-- BARRE DE RECHERCHE                         -->
        <!-- ========================================== -->
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="🔍 Rechercher un plat (par nom ou description)...">
            <div id="searchResultsCount" class="search-count"></div>
        </div>
        
        <!-- Boutons de catégories -->
        <div class="categories">
            <button class="category-btn active" data-category="all">Tous</button>
            @foreach($categories as $category)
                <button class="category-btn" data-category="{{ $category->id }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
        
        <!-- Grille des plats -->
        <div class="menu-grid" id="menuGrid">
            @foreach($dishes as $dish)
                <div class="menu-card" data-category="{{ $dish->category_id }}">
                    <!-- Affichage de l'image -->
                    @if($dish->image_url)
                        <img src="{{ asset($dish->image_url) }}" alt="{{ $dish->name }}" class="dish-image">
                    @else
                        <div class="dish-image" style="background: #ddd; display: flex; align-items: center; justify-content: center; color: #999;">
                            🖼️ Pas d'image
                        </div>
                    @endif
                    
                    <div class="card-content">
                        <div class="dish-name">
                            {{ $dish->name }}
                            @if($dish->is_recommended)
                                <span class="recommended">Recommandé</span>
                            @endif
                        </div>
                        <div class="dish-description">{{ $dish->description }}</div>
                        <div class="dish-price">{{ number_format($dish->price, 2) }} MAD</div>
                        
                        <!-- ========================================== -->
                        <!-- AFFICHAGE DES NOTES ET AVIS               -->
                        <!-- ========================================== -->
                        <div class="rating">
                            <div class="stars">
                                @php
                                    $fullStars = floor($dish->rating_avg ?? 0);
                                    $halfStar = ($dish->rating_avg ?? 0) - $fullStars >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp
                                
                                @for($i = 0; $i < $fullStars; $i++)
                                    <span class="star filled">★</span>
                                @endfor
                                
                                @if($halfStar)
                                    <span class="star half-filled">½</span>
                                @endif
                                
                                @for($i = 0; $i < $emptyStars; $i++)
                                    <span class="star">☆</span>
                                @endfor
                            </div>
                            <span class="rating-value">
                                {{ number_format($dish->rating_avg ?? 0, 1) }} ({{ $dish->rating_count ?? 0 }} avis)
                            </span>
                            <a href="{{ route('reviews.show', $dish->id) }}" class="review-link">📝 Donner mon avis</a>
                        </div>
                        
                        <!-- Formulaire ajouter au panier -->
                        <form action="{{ route('cart.add', $dish->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="add-to-cart">
                                🛒 Ajouter au panier
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        
        <footer>
            Pour passer commande, merci de faire appel à notre service.
        </footer>
    </div>
    
    <script>
        // ==========================================
        // ÉLÉMENTS DOM
        // ==========================================
        const buttons = document.querySelectorAll('.category-btn');
        const cards = document.querySelectorAll('.menu-card');
        const searchInput = document.getElementById('searchInput');
        const searchCount = document.getElementById('searchResultsCount');
        
        // ==========================================
        // FONCTION DE FILTRAGE COMBINÉ
        // ==========================================
        function filterMenu() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const activeCategory = document.querySelector('.category-btn.active').dataset.category;
            let visibleCount = 0;
            
            cards.forEach(card => {
                const dishName = card.querySelector('.dish-name').textContent.toLowerCase();
                const dishDescription = card.querySelector('.dish-description').textContent.toLowerCase();
                
                // Vérifier la recherche
                const matchesSearch = searchTerm === '' || 
                                     dishName.includes(searchTerm) || 
                                     dishDescription.includes(searchTerm);
                
                // Vérifier la catégorie
                const matchesCategory = activeCategory === 'all' || 
                                       card.dataset.category === activeCategory;
                
                // Afficher ou cacher
                if (matchesSearch && matchesCategory) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Mettre à jour le compteur de résultats
            if (searchCount) {
                if (searchTerm === '') {
                    searchCount.innerHTML = '';
                } else {
                    searchCount.innerHTML = `<strong>${visibleCount}</strong> résultat(s) trouvé(s) pour "<strong>${searchTerm}</strong>"`;
                }
            }
        }
        
        // ==========================================
        // ÉCOUTEUR DE SAISIE POUR LA RECHERCHE
        // ==========================================
        if (searchInput) {
            searchInput.addEventListener('input', filterMenu);
        }
        
        // ==========================================
        // FILTRAGE PAR CATÉGORIE
        // ==========================================
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                filterMenu();
            });
        });
    </script>
</body>
</html>