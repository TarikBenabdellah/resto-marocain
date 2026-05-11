<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes - Admin</title>
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
        .sidebar a:hover, .sidebar a.active { background: #e67e22; }
        
        .main-content { margin-left: 260px; padding: 20px; }
        
        .card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        
        .status {
            padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;
        }
        .status-pending { background: #f39c12; color: white; }
        .status-preparing { background: #3498db; color: white; }
        .status-ready { background: #2ecc71; color: white; }
        .status-completed { background: #27ae60; color: white; }
        .status-cancelled { background: #e74c3c; color: white; }
        
        .btn { padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 0.8rem; }
        .btn-primary { background: #e67e22; color: white; }
        
        .alert-success { background: #27ae60; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .alert-error { background: #e74c3c; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        
        .pagination { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🍽️ Resto Admin</h2>
        <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
        <a href="{{ route('admin.orders') }}" class="active">📦 Commandes</a>
        <a href="{{ route('admin.dishes') }}">🍕 Gestion des plats</a>
        <a href="/">🏠 Voir le site</a>
    </div>
    
    <div class="main-content">
        @if(session('success')) <div class="alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert-error">{{ session('error') }}</div> @endif
        
        <div class="card">
            <h3>📦 Toutes les commandes</h3>
            <table>
                <thead>
                    <tr><th>ID</th><th>N° commande</th><th>Client</th><th>Total</th><th>Statut</th><th>Date</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ number_format($order->total_amount, 2) }} MAD</td>
                        <td><span class="status status-{{ $order->status }}">{{ $order->status }}</span></td>
                        <td>{{ $order->created_at }}</td>
                        <td><a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary">Voir</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">{{ $orders->links() }}</div>
        </div>
    </div>
</body>
</html>