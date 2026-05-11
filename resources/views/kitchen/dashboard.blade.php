<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Cuisine - Resto Marocain</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #1a1a2e;
            padding: 20px;
        }
        
        .header {
            background: #16213e;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 { color: #e67e22; }
        
        .stats {
            display: flex;
            gap: 15px;
        }
        
        .stat-badge {
            background: #0f3460;
            padding: 8px 15px;
            border-radius: 20px;
        }
        
        .stat-badge span {
            font-weight: bold;
            color: #e67e22;
        }
        
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .column {
            background: #0f3460;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .column-header {
            background: #16213e;
            color: white;
            padding: 15px;
            font-weight: bold;
            border-bottom: 3px solid #e67e22;
        }
        
        .column-content {
            padding: 15px;
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .order-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .order-number {
            font-weight: bold;
            color: #e67e22;
        }
        
        .order-time {
            font-size: 0.8rem;
            color: #888;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-primary { background: #e67e22; color: white; }
        .btn-warning { background: #f39c12; color: white; }
        
        .empty-message {
            text-align: center;
            color: white;
            padding: 30px;
            opacity: 0.7;
        }
        
        .admin-link {
            color: #e67e22;
            text-decoration: none;
            margin-left: 20px;
        }
        
        .alert-success {
            background: #27ae60;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .view-link {
            display: inline-block;
            margin-top: 10px;
            color: #e67e22;
            text-decoration: none;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>🍳 Interface Cuisine</h1>
            <small>Resto Marocain Casa</small>
        </div>
        <div class="stats">
            <div class="stat-badge">🕒 En attente: <span>{{ $stats['pending'] }}</span></div>
            <div class="stat-badge">👨‍🍳 En préparation: <span>{{ $stats['preparing'] }}</span></div>
            <div class="stat-badge">✅ Prêtes: <span>{{ $stats['ready'] }}</span></div>
            <a href="/admin" class="admin-link">📊 Admin</a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="orders-grid">
        <!-- Colonne : En attente -->
        <div class="column">
            <div class="column-header">🕒 En attente ({{ $pendingOrders->count() }})</div>
            <div class="column-content">
                @forelse($pendingOrders as $order)
                    <div class="order-card">
                        <div class="order-number">#{{ $order->order_number }}</div>
                        <div class="order-time">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}</div>
                        <div><strong>{{ $order->customer_name }}</strong></div>
                        <div>{{ number_format($order->total_amount, 2) }} MAD</div>
                        <form action="{{ route('kitchen.order.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="preparing">
                            <button type="submit" class="btn btn-primary">👨‍🍳 Démarrer</button>
                        </form>
                        <a href="{{ route('kitchen.order.show', $order->id) }}" class="view-link">📋 Voir détails</a>
                    </div>
                @empty
                    <div class="empty-message">Aucune commande en attente</div>
                @endforelse
            </div>
        </div>
        
        <!-- Colonne : En préparation -->
        <div class="column">
            <div class="column-header">👨‍🍳 En préparation ({{ $preparingOrders->count() }})</div>
            <div class="column-content">
                @forelse($preparingOrders as $order)
                    <div class="order-card">
                        <div class="order-number">#{{ $order->order_number }}</div>
                        <div class="order-time">Démarrée à {{ \Carbon\Carbon::parse($order->updated_at)->format('H:i') }}</div>
                        <div><strong>{{ $order->customer_name }}</strong></div>
                        <div>{{ number_format($order->total_amount, 2) }} MAD</div>
                        <form action="{{ route('kitchen.order.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="ready">
                            <button type="submit" class="btn btn-warning">✅ Prête</button>
                        </form>
                        <a href="{{ route('kitchen.order.show', $order->id) }}" class="view-link">📋 Voir détails</a>
                    </div>
                @empty
                    <div class="empty-message">Aucune commande en préparation</div>
                @endforelse
            </div>
        </div>
        
        <!-- Colonne : Prêtes -->
        <div class="column">
            <div class="column-header">✅ Prêtes ({{ $readyOrders->count() }})</div>
            <div class="column-content">
                @forelse($readyOrders as $order)
                    <div class="order-card">
                        <div class="order-number">#{{ $order->order_number }}</div>
                        <div class="order-time">Prête à {{ \Carbon\Carbon::parse($order->updated_at)->format('H:i') }}</div>
                        <div><strong>{{ $order->customer_name }}</strong></div>
                        <div>{{ number_format($order->total_amount, 2) }} MAD</div>
                        <a href="{{ route('kitchen.order.show', $order->id) }}" class="view-link">📋 Voir détails</a>
                    </div>
                @empty
                    <div class="empty-message">Aucune commande prête</div>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>