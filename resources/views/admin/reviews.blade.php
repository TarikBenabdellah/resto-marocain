<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des avis - Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100%;
            background: #2c3e50;
            color: white;
            padding: 20px;
            overflow-y: auto;
        }
        
        .sidebar h2 {
            color: #e67e22;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            margin: 5px 0;
            border-radius: 8px;
            transition: background 0.3s;
        }
        
        .sidebar a:hover, .sidebar a.active {
            background: #e67e22;
        }
        
        /* Contenu principal */
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .card h2 {
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #e67e22;
            padding-bottom: 10px;
            display: inline-block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #f8f9fa;
        }
        
        .rating-stars {
            color: #f39c12;
        }
        
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.8rem;
            margin: 2px;
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        
        .btn-success:hover {
            background: #219a52;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .alert-success {
            background: #27ae60;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .badge-pending {
            background: #f39c12;
            color: white;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
        }
        
        .badge-approved {
            background: #27ae60;
            color: white;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
        }
        
        .comment-text {
            max-width: 300px;
            word-wrap: break-word;
        }
        
        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        @media (max-width: 1000px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
                margin-left: 200px;
            }
            table {
                font-size: 0.8rem;
            }
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🍽️ Resto Admin</h2>
        <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
        <a href="{{ route('admin.orders') }}">📦 Commandes</a>
        <a href="{{ route('admin.dishes') }}">🍕 Gestion des plats</a>
        <a href="/admin/reviews" class="active">⭐ Gestion des avis</a>
        <a href="/">🏠 Voir le site</a>
        <a href="/kitchen">🍳 Interface Cuisine</a>
        <hr style="margin: 20px 0; border-color: #444;">
        <a href="{{ route('admin.logout') }}" style="color: #e74c3c;">🚪 Déconnexion</a>
    </div>
    
    <div class="main-content">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        
        <h1>⭐ Gestion des avis clients</h1>
        
        <!-- ========================================== -->
        <!-- AVIS EN ATTENTE D'APPROBATION               -->
        <!-- ========================================== -->
        <div class="card">
            <h2>🕒 Avis en attente d'approbation</h2>
            
            @if(count($pendingReviews) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Plat</th>
                            <th>Client</th>
                            <th>Note</th>
                            <th>Avis</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingReviews as $review)
                        <tr>
                            <td><strong>{{ $review->dish_name }}</strong></td>
                            <td>{{ $review->customer_name }}</td>
                            <td class="rating-stars">
                                @for($i = 0; $i < $review->rating; $i++)
                                    ★
                                @endfor
                                @for($i = $review->rating; $i < 5; $i++)
                                    ☆
                                @endfor
                            </td>
                            <td class="comment-text">{{ $review->comment }}</td>
                            <td>{{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Approuver cet avis ?')">✅ Approuver</button>
                                </form>
                                <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer cet avis ?')">🗑️ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">
                    📭 Aucun avis en attente d'approbation
                </div>
            @endif
        </div>
        
        <!-- ========================================== -->
        <!-- AVIS APPROUVÉS                              -->
        <!-- ========================================== -->
        <div class="card">
            <h2>✅ Avis approuvés</h2>
            
            @if(count($approvedReviews) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Plat</th>
                            <th>Client</th>
                            <th>Note</th>
                            <th>Avis</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedReviews as $review)
                        <tr>
                            <td><strong>{{ $review->dish_name }}</strong></td>
                            <td>{{ $review->customer_name }}</td>
                            <td class="rating-stars">
                                @for($i = 0; $i < $review->rating; $i++)
                                    ★
                                @endfor
                                @for($i = $review->rating; $i < 5; $i++)
                                    ☆
                                @endfor
                            </td>
                            <td class="comment-text">{{ $review->comment }}</td>
                            <td>{{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer cet avis ?')">🗑️ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">
                    📭 Aucun avis approuvé pour le moment
                </div>
            @endif
        </div>
    </div>
</body>
</html>