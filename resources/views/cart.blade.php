<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - Resto Marocain</title>
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
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #e67e22;
            margin-bottom: 20px;
            text-align: center;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #f8f9fa;
            color: #333;
        }
        
        .quantity-input {
            width: 60px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        
        .total {
            font-size: 1.3rem;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e67e22;
        }
        
        .btn {
            background: #e67e22;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #d35400;
        }
        
        .btn-danger {
            background: #e74c3c;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .btn-secondary {
            background: #95a5a6;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        
        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .empty-cart {
            text-align: center;
            padding: 50px;
            color: #999;
        }
        
        .empty-cart a {
            color: #e67e22;
            text-decoration: none;
        }
        
        .alert-success {
            background: #27ae60;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .alert-error {
            background: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #e67e22;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 Mon Panier</h1>
        
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
        
        @if(empty($cart))
            <div class="empty-cart">
                <p>🛍️ Votre panier est vide</p>
                <a href="/menu">← Retour au menu</a>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Plat</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                        <tr>
                            <td>
                                @if(isset($item['image_url']) && $item['image_url'])
                                    <img src="{{ asset($item['image_url']) }}" alt="{{ $item['name'] }}" class="product-image">
                                @else
                                    <div style="width: 50px; height: 50px; background: #ddd; border-radius: 5px;"></div>
                                @endif
                            </td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ number_format($item['price'], 2) }} MAD</td>
                            <td>
                                <form action="{{ route('cart.update', $id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" class="quantity-input" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td>{{ number_format($item['price'] * $item['quantity'], 2) }} MAD</td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px;">❌</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="total">
                Total TTC: {{ number_format($total, 2) }} MAD
            </div>
            
            <div class="actions">
                <a href="/menu" class="btn btn-secondary">← Continuer mes achats</a>
                <a href="{{ route('checkout') }}" class="btn">📝 Passer commande</a>
            </div>
        @endif
        
        <a href="/menu" class="back-link">← Retour au menu</a>
    </div>
</body>
</html>