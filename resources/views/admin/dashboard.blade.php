<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Resto Marocain</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        /* Cartes statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: #e67e22;
        }
        
        /* Grille des graphiques */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .chart-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .chart-card h3 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
            border-bottom: 2px solid #e67e22;
            padding-bottom: 10px;
            display: inline-block;
            width: auto;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 15px;
        }
        
        canvas {
            max-height: 250px;
            width: 100% !important;
        }
        
        /* Tableau */
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .card h3 {
            margin-bottom: 15px;
            color: #333;
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
        
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .status-pending { background: #f39c12; color: white; }
        .status-preparing { background: #3498db; color: white; }
        .status-ready { background: #2ecc71; color: white; }
        .status-completed { background: #27ae60; color: white; }
        .status-cancelled { background: #e74c3c; color: white; }
        
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.8rem;
        }
        
        .btn-primary { background: #e67e22; color: white; }
        .btn-primary:hover { background: #d35400; }
        
        .alert-success {
            background: #27ae60;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .view-link {
            color: #e67e22;
            text-decoration: none;
        }
        
        .view-link:hover {
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 1000px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
            .sidebar {
                width: 200px;
            }
            .main-content {
                margin-left: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🍽️ Resto Admin</h2>
        <a href="{{ route('admin.dashboard') }}" class="active">📊 Dashboard</a>
        <a href="{{ route('admin.orders') }}">📦 Commandes</a>
        <a href="{{ route('admin.dishes') }}">🍕 Gestion des plats</a>
        <a href="/">🏠 Voir le site</a>
        <a href="/kitchen">🍳 Interface Cuisine</a>
        <a href="{{ route('admin.reviews') }}">⭐ Gestion des avis</a>
        <hr style="margin: 20px 0; border-color: #444;">
        <a href="{{ route('admin.logout') }}" style="color: #e74c3c;">🚪 Déconnexion</a>
    </div>
    
    <div class="main-content">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif
        
        <h1>📊 Dashboard Administrateur</h1>
        <p style="color: #666; margin-bottom: 20px;">Bienvenue dans votre espace de gestion</p>
        
        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>📦 Total commandes</h3>
                <div class="value">{{ $totalOrders }}</div>
            </div>
            <div class="stat-card">
                <h3>💰 Chiffre d'affaires</h3>
                <div class="value">{{ number_format($totalRevenue, 2) }} MAD</div>
            </div>
            <div class="stat-card">
                <h3>🕒 Commandes en attente</h3>
                <div class="value">{{ $pendingOrders }}</div>
            </div>
            <div class="stat-card">
                <h3>📅 Commandes aujourd'hui</h3>
                <div class="value">{{ $todayOrders }}</div>
            </div>
        </div>
        
        <!-- ========================================== -->
        <!-- GRAPHIQUES                                 -->
        <!-- ========================================== -->
        <div class="charts-grid">
            <!-- Graphique 1 : Ventes par mois -->
            <div class="chart-card">
                <h3>📈 Ventes par mois</h3>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
            
            <!-- Graphique 2 : Commandes par statut -->
            <div class="chart-card">
                <h3>🥧 Commandes par statut</h3>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
            
            <!-- Graphique 3 : Top 5 plats -->
            <div class="chart-card">
                <h3>🏆 Top 5 des plats vendus</h3>
                <div class="chart-container">
                    <canvas id="topDishesChart"></canvas>
                </div>
            </div>
            
            <!-- Graphique 4 : Commandes par jour -->
            <div class="chart-card">
                <h3>📊 Commandes par jour de la semaine</h3>
                <div class="chart-container">
                    <canvas id="dailyOrdersChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Commandes récentes -->
        <div class="card">
            <h3>📋 Dernières commandes</h3>
            <table>
                <thead>
                    <tr><th>N° commande</th><th>Client</th><th>Total</th><th>Statut</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</span></td>
                        <td>{{ $order->customer_name }}</span></td>
                        <td>{{ number_format($order->total_amount, 2) }} MAD</span></td>
                        <td><span class="status status-{{ $order->status }}">{{ $order->status }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</span></td>
                        <td><a href="{{ route('admin.orders.show', $order->id) }}" class="view-link">Voir</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        // ==========================================
        // GRAPHIQUE 1 : VENTES PAR MOIS (Ligne)
        // ==========================================
        const ctx1 = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Chiffre d\'affaires (MAD)',
                    data: {!! json_encode($salesData) !!},
                    backgroundColor: 'rgba(230, 126, 34, 0.1)',
                    borderColor: '#e67e22',
                    borderWidth: 2,
                    pointBackgroundColor: '#e67e22',
                    pointBorderColor: '#fff',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.raw.toFixed(2) + ' MAD';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' MAD';
                            }
                        }
                    }
                }
            }
        });
        
        // ==========================================
        // GRAPHIQUE 2 : COMMANDES PAR STATUT (Camembert)
        // ==========================================
        const ctx2 = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['En attente', 'En préparation', 'Prête', 'Terminée', 'Annulée'],
                datasets: [{
                    data: [
                        {{ $statusCounts['pending'] ?? 0 }},
                        {{ $statusCounts['preparing'] ?? 0 }},
                        {{ $statusCounts['ready'] ?? 0 }},
                        {{ $statusCounts['completed'] ?? 0 }},
                        {{ $statusCounts['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: ['#f39c12', '#3498db', '#2ecc71', '#27ae60', '#e74c3c'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // ==========================================
        // GRAPHIQUE 3 : TOP 5 PLATS (Barres)
        // ==========================================
        const ctx3 = document.getElementById('topDishesChart').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dishNames) !!},
                datasets: [{
                    label: 'Nombre de fois commandé',
                    data: {!! json_encode($dishQuantities) !!},
                    backgroundColor: '#e67e22',
                    borderRadius: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        
        // ==========================================
        // GRAPHIQUE 4 : COMMANDES PAR JOUR (Barres)
        // ==========================================
        const ctx4 = document.getElementById('dailyOrdersChart').getContext('2d');
        new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: {!! json_encode($weekDays) !!},
                datasets: [{
                    label: 'Nombre de commandes',
                    data: {!! json_encode($dailyOrders) !!},
                    backgroundColor: '#3498db',
                    borderRadius: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>