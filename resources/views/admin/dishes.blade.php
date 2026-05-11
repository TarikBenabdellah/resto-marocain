<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des plats - Admin</title>
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
        
        .card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; }
        th { background: #f8f9fa; }
        
        .btn { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #e67e22; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-warning { background: #f39c12; color: white; }
        .btn-sm { padding: 4px 8px; font-size: 0.8rem; }
        
        .alert-success { background: #27ae60; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .alert-error { background: #e74c3c; color: white; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        
        form { display: inline; }
        input, select, textarea { width: 100%; padding: 8px; margin: 5px 0 15px; border: 1px solid #ddd; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        
        .edit-form { display: none; margin-top: 20px; padding: 20px; background: #f9f9f9; border-radius: 10px; }
        
        .dish-image-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .no-image {
            width: 50px;
            height: 50px;
            background: #ddd;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🍽️ Resto Admin</h2>
        <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
        <a href="{{ route('admin.orders') }}">📦 Commandes</a>
        <a href="{{ route('admin.dishes') }}" class="active">🍕 Gestion des plats</a>
        <a href="/">🏠 Voir le site</a>
        <a href="/kitchen">🍳 Interface Cuisine</a>
        <hr style="margin: 20px 0; border-color: #444;">
        <a href="{{ route('admin.logout') }}" style="color: #e74c3c;">🚪 Déconnexion</a>
    </div>
    
    <div class="main-content">
        @if(session('success')) <div class="alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert-error">{{ session('error') }}</div> @endif
        
        <!-- Formulaire d'ajout -->
        <div class="card">
            <h3>➕ Ajouter un plat</h3>
            <form action="{{ route('admin.dishes.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="category_id" required>
                        <option value="">Sélectionner</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Nom du plat</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Prix (MAD)</label>
                    <input type="number" step="0.01" name="price" required>
                </div>
                
                <div class="form-group">
                    <label>Image du plat</label>
                    <input type="file" name="image" accept="image/*">
                    <small style="color: #666;">Formats acceptés: JPG, PNG, GIF (max 2MB)</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_recommended" value="1"> Recommandé
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_available" value="1" checked> Disponible
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">➕ Ajouter le plat</button>
            </form>
        </div>
        
        <!-- Liste des plats -->
        <div class="card">
            <h3>🍕 Liste des plats</h3>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Recommandé</th>
                        <th>Disponible</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dishes as $dish)
                    <tr>
                        <td>
                            @if($dish->image_url)
                                <img src="{{ asset($dish->image_url) }}" alt="{{ $dish->name }}" class="dish-image-preview">
                            @else
                                <div class="no-image">Pas d'image</div>
                            @endif
                        </td>
                        <td>{{ $dish->id }}</td>
                        <td>{{ $dish->name }}</td>
                        <td>{{ $dish->category_name }}</td>
                        <td>{{ number_format($dish->price, 2) }} MAD</td>
                        <td>{{ $dish->is_recommended ? '✅' : '❌' }}</td>
                        <td>{{ $dish->is_available ? '✅' : '❌' }}</td>
                        <td>
                            <button onclick="showEditForm({{ $dish->id }}, '{{ addslashes($dish->name) }}', '{{ addslashes($dish->description) }}', {{ $dish->price }}, {{ $dish->category_id }}, {{ $dish->is_recommended }}, {{ $dish->is_available }})" class="btn btn-warning btn-sm">✏️ Modifier</button>
                            <form action="{{ route('admin.dishes.delete', $dish->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce plat ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="edit-row-{{ $dish->id }}" class="edit-form">
                        <td colspan="8">
                            <form action="{{ route('admin.dishes.edit', $dish->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="category_id" id="edit-cat-{{ $dish->id }}">
                                <input type="text" name="name" id="edit-name-{{ $dish->id }}" required>
                                <textarea name="description" id="edit-desc-{{ $dish->id }}" rows="2"></textarea>
                                <input type="number" step="0.01" name="price" id="edit-price-{{ $dish->id }}" required>
                                <input type="file" name="image" accept="image/*">
                                <small>Laissez vide pour garder l'image actuelle</small>
                                <label><input type="checkbox" name="is_recommended" value="1" id="edit-reco-{{ $dish->id }}"> Recommandé</label>
                                <label><input type="checkbox" name="is_available" value="1" id="edit-avail-{{ $dish->id }}"> Disponible</label>
                                <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                                <button type="button" onclick="hideEditForm({{ $dish->id }})" class="btn btn-danger btn-sm">Annuler</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">{{ $dishes->links() }}</div>
        </div>
    </div>
    
   <script>
    function showEditForm(id, name, desc, price, catId, recommended, available) {
        // Cacher tous les formulaires
        document.querySelectorAll('.edit-form').forEach(el => el.style.display = 'none');
        
        // Afficher le formulaire correspondant
        const row = document.getElementById('edit-row-' + id);
        row.style.display = 'table-row';
        
        // Remplir les champs
        document.getElementById('edit-name-' + id).value = name;
        document.getElementById('edit-desc-' + id).value = desc || '';
        document.getElementById('edit-price-' + id).value = price;
        document.getElementById('edit-cat-' + id).value = catId;
        document.getElementById('edit-reco-' + id).checked = recommended == 1;
        document.getElementById('edit-avail-' + id).checked = available == 1;
    }
    
    function hideEditForm(id) {
        document.getElementById('edit-row-' + id).style.display = 'none';
    }
</script>
</body>
</html>