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
        
        h1 {
            text-align: center;
            color: #e67e22;
            margin-bottom: 10px;
            font-size: 2.5rem;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 40px;
        }
        
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
        
        .empty {
            text-align: center;
            color: #999;
            padding: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍽️ Notre Menu</h1>
        <div class="subtitle">Table 27 - TERRASSE</div>
        
        <div class="categories">
            <button class="category-btn active" data-category="all">Tous</button>
            @foreach($categories as $category)
                <button class="category-btn" data-category="{{ $category->id }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
        
        <div class="menu-grid" id="menuGrid">
            @foreach($dishes as $dish)
                <div class="menu-card" data-category="{{ $dish->category_id }}">
                    <div class="card-content">
                        <div class="dish-name">
                            {{ $dish->name }}
                            @if($dish->is_recommended)
                                <span class="recommended">Recommandé</span>
                            @endif
                        </div>
                        <div class="dish-description">{{ $dish->description }}</div>
                        <div class="dish-price">{{ number_format($dish->price, 2) }} MAD</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <script>
        const buttons = document.querySelectorAll('.category-btn');
        const cards = document.querySelectorAll('.menu-card');
        
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                // Retirer la classe active de tous les boutons
                buttons.forEach(btn => btn.classList.remove('active'));
                // Ajouter la classe active au bouton cliqué
                button.classList.add('active');
                
                const category = button.dataset.category;
                
                cards.forEach(card => {
                    if (category === 'all') {
                        card.style.display = 'block';
                    } else {
                        if (card.dataset.category === category) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>