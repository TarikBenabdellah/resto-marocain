<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails commande #{{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; }
        
        .sidebar {
            position: fixed; left: 0; top: 0; width: 260px; height: 100%;
            background: #2c3e50; color: white; padding: 20px;
        }
        .sidebar h2 { color: #e67e22; margin-bottom: 30px; text-align: center; }
        .sidebar a {
            display: block; color: white; text-decoration: none;
            padding: 12px 15px; margin: 5px 0; border-radius: 8px;
        }
        .sidebar a:hover { background: #e67e22; }
        
        .main-content { margin-left: 260px; padding: 20px; }
        
        .card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        
        .status {
            padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;
        }
        .status-pending { background: #f39c12; color: white; }
        .status-preparing { background: #3498db; color: white; }
        .status-ready { background: #2ecc71; color: white; }
        .status-completed { background: #27ae60; color: white; }
        .status-cancelled { background: #e74c3c; color: white; }
        
        .btn { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-primary { background: #e67e22; color: white; }
        .btn-primary:hover { background: #d35400; }
        
        .alert-success { background: #27ae60; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        
        form { display: inline; }
        select { padding: 8px; margin-right: 10px; border-radius: 5px; }
        
        .back-link { display: inline-block; margin-top: 20px; color: #e67e22; text-decoration: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🍽️ Resto Admin</h2>
        <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
        <a href="{{ route('admin.orders') }}">📦 Commandes</a>
        <a href="{{ route('admin.dishes') }}">🍕 Gestion des plats</a>
        <a href="/">🏠 Voir le site</a>
    </div>
    
    <div class="main-content">
        @if(session('success')) <div class="alert-success">{{ session('success') }}</div> @endif
        
        <div class="card">
            <h3>📦 Commande #{{ $order->order_number }}</h3>
            <p><strong>Client :</strong> {{ $order->customer_name }}</p>
            <p><strong>Téléphone :</strong> {{ $order->customer_phone }}</p>
            <p><strong>Type :</strong> {{ $order->order_type == 'dine_in' ? 'Sur place' : 'À emporter' }}</p>
            <p><strong>Date :</strong> {{ $order->created_at }}</p>
            
            <h4 style="margin: 20px 0 10px;">Détails des plats</h4>
            <table>
                <thead><tr><th>Plat</th><th>Quantité</th><th>Prix unitaire</th><th>Total</th></tr></thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item->dish_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }} MAD</td>
                        <td>{{ number_format($item->price * $item->quantity, 2) }} MAD</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <p style="margin-top: 20px; font-size: 1.2rem; font-weight: bold;">Total: {{ number_format($order->total_amount, 2) }} MAD</p>
        </div>
        
        <div class="card">
            <h3>🔄 Changer le statut</h3>
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <select name="status">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>En préparation</option>
                    <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Prête</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Terminée</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                </select>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
        
        <a href="{{ route('admin.orders') }}" class="back-link">← Retour aux commandes</a>
    </div>
</body>
</html>