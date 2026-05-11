<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avis - {{ $dish->name }}</title>
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
            max-width: 800px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #e67e22;
            margin-bottom: 10px;
        }
        
        .dish-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .dish-name {
            font-size: 1.2rem;
            color: #333;
        }
        
        .back-link {
            color: #e67e22;
            text-decoration: none;
        }
        
        .stars {
            display: inline-flex;
            gap: 2px;
        }
        
        .star {
            font-size: 1.2rem;
        }
        
        .star.filled {
            color: #f39c12;
        }
        
        .star {
            color: #ddd;
        }
        
        .rating-summary {
            display: flex;
            gap: 30px;
            align-items: center;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .big-rating {
            text-align: center;
        }
        
        .big-rating .value {
            font-size: 3rem;
            font-weight: bold;
            color: #e67e22;
        }
        
        .distribution {
            flex: 1;
        }
        
        .dist-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 5px 0;
        }
        
        .dist-bar .label {
            width: 30px;
            font-size: 0.8rem;
        }
        
        .dist-bar .bar {
            flex: 1;
            height: 8px;
            background: #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .dist-bar .bar-fill {
            height: 100%;
            background: #f39c12;
            width: 0%;
        }
        
        .review-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        
        .review-name {
            font-weight: bold;
            color: #333;
        }
        
        .review-date {
            color: #999;
            font-size: 0.8rem;
        }
        
        .review-comment {
            color: #666;
            line-height: 1.5;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        .btn {
            background: #e67e22;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
        }
        
        .btn:hover {
            background: #d35400;
        }
        
        .alert-success {
            background: #27ae60;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background: #e74c3c;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .rating-input {
            display: flex;
            gap: 15px;
            font-size: 2rem;
            cursor: pointer;
        }
        
        .rating-input span {
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .rating-input span:hover {
            transform: scale(1.2);
        }
        
        .required {
            color: #e74c3c;
        }
        
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #eee;
        }
        
        @media (max-width: 600px) {
            .rating-summary {
                flex-direction: column;
                text-align: center;
            }
            .distribution {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="dish-info">
                <a href="/menu-client" class="back-link">← Retour au menu</a>
                <span class="dish-name">🍽️ {{ $dish->name }}</span>
            </div>
            
            <h1>📝 Avis et notes</h1>
            
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif
            
            <!-- Résumé des notes -->
            <div class="rating-summary">
                <div class="big-rating">
                    <div class="value">{{ number_format($dish->rating_avg ?? 0, 1) }}</div>
                    <div class="stars">
                        @php
                            $fullStars = floor($dish->rating_avg ?? 0);
                            $emptyStars = 5 - $fullStars;
                        @endphp
                        @for($i = 0; $i < $fullStars; $i++)
                            <span class="star filled">★</span>
                        @endfor
                        @for($i = $fullStars; $i < 5; $i++)
                            <span class="star">☆</span>
                        @endfor
                    </div>
                    <div>{{ $dish->rating_count ?? 0 }} avis</div>
                </div>
                
                <div class="distribution">
                    @for($i = 5; $i >= 1; $i--)
                        @php
                            $total = $dish->rating_count ?? 0;
                            $count = $ratingDistribution[$i] ?? 0;
                            $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                        @endphp
                        <div class="dist-bar">
                            <span class="label">{{ $i }} ★</span>
                            <div class="bar">
                                <div class="bar-fill" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span>{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>
            
            <!-- Formulaire d'avis -->
            <div class="card" style="margin-bottom: 20px;">
                <h3>✍️ Donnez votre avis</h3>
                <form action="{{ route('reviews.store', $dish->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Votre nom <span class="required">*</span></label>
                        <input type="text" name="customer_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Votre email (optionnel)</label>
                        <input type="email" name="customer_email">
                    </div>
                    
                    <div class="form-group">
                        <label>Note <span class="required">*</span></label>
                        <div class="rating-input" id="ratingInput">
                            <span data-rating="1">☆</span>
                            <span data-rating="2">☆</span>
                            <span data-rating="3">☆</span>
                            <span data-rating="4">☆</span>
                            <span data-rating="5">☆</span>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Votre avis <span class="required">*</span></label>
                        <textarea name="comment" rows="4" required placeholder="Partagez votre expérience..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn">📝 Publier mon avis</button>
                </form>
            </div>
            
            <!-- Liste des avis -->
            <h3>📖 Avis des clients ({{ $reviews->count() }})</h3>
            
            @forelse($reviews as $review)
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-name">{{ $review->customer_name }}</span>
                        <span class="review-date">{{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}</span>
                    </div>
                    <div class="stars" style="margin-bottom: 8px;">
                        @for($i = 0; $i < $review->rating; $i++)
                            <span class="star filled">★</span>
                        @endfor
                        @for($i = $review->rating; $i < 5; $i++)
                            <span class="star">☆</span>
                        @endfor
                    </div>
                    <div class="review-comment">{{ $review->comment }}</div>
                </div>
            @empty
                <div class="card" style="text-align: center; color: #999;">
                    📭 Aucun avis pour le moment. Soyez le premier à donner votre avis !
                </div>
            @endforelse
        </div>
    </div>
    
    <script>
        // Sélection des étoiles
        const stars = document.querySelectorAll('#ratingInput span');
        const ratingInput = document.getElementById('ratingValue');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                ratingInput.value = rating;
                
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.innerHTML = '★';
                        s.style.color = '#f39c12';
                    } else {
                        s.innerHTML = '☆';
                        s.style.color = '#ddd';
                    }
                });
            });
        });
    </script>
</body>
</html>