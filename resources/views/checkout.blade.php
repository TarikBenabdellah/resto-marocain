<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation commande - Resto Marocain</title>
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
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #e67e22;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        
        /* Section récapitulatif */
        .recap {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .recap h2 {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 15px;
            border-bottom: 2px solid #e67e22;
            padding-bottom: 5px;
            display: inline-block;
        }
        
        .recap-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .recap-total {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-weight: bold;
            font-size: 1.2rem;
            color: #e67e22;
            border-top: 2px solid #e67e22;
            margin-top: 10px;
        }
        
        /* Formulaire */
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #e67e22;
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
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #d35400;
        }
        
        .btn-secondary {
            background: #95a5a6;
            margin-top: 10px;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        
        .error {
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 5px;
        }
        
        .alert-error {
            background: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ddd;
        }
        
        .back-link {
            display: block;
            text-align: center;
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
        <h1>📝 Validation de commande</h1>
        <div class="subtitle">Merci de confirmer vos informations</div>
        
        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Récapitulatif du panier -->
        <div class="recap">
            <h2>🍽️ Votre commande</h2>
            @foreach($cart as $item)
                <div class="recap-item">
                    <span>{{ $item['quantity'] }} x {{ $item['name'] }}</span>
                    <span>{{ number_format($item['price'] * $item['quantity'], 2) }} MAD</span>
                </div>
            @endforeach
            <div class="recap-total">
                <span>Total TTC</span>
                <span>{{ number_format($total, 2) }} MAD</span>
            </div>
        </div>
        
        <!-- Formulaire client -->
        <form action="{{ route('order.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="customer_name">Nom complet *</label>
                <input type="text" id="customer_name" name="customer_name" required placeholder="Ex: Ahmed Benani">
                @error('customer_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="customer_phone">Téléphone *</label>
                <input type="tel" id="customer_phone" name="customer_phone" required placeholder="Ex: 06 XX XX XX XX">
                @error('customer_phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="order_type">Type de commande *</label>
                <select id="order_type" name="order_type" required>
                    <option value="dine_in">🍽️ Sur place (Table 27 - Terrasse)</option>
                    <option value="takeaway">🛍️ À emporter</option>
                </select>
                @error('order_type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn">✅ Confirmer ma commande</button>
        </form>
        
        <a href="{{ route('cart.index') }}" class="back-link">← Retour au panier</a>
    </div>
</body>
</html>