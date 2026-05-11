<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resto Marocain Casa - Authentic Moroccan Cuisine</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* ========== SLIDER / HERO SECTION ========== */
        .hero {
            position: relative;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .slide.active {
            opacity: 1;
        }

        .slide1 { background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1517244683847-7456b63c5969?w=1600'); }
        .slide2 { background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1600'); }
        .slide3 { background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1600'); }

        /* Contenu du hero */
        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 10;
            width: 100%;
            padding: 0 20px;
            animation: fadeInUp 1.5s ease-out;
        }

        .hero-content h1 {
            font-size: 4rem;
            margin-bottom: 20px;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-content h1 span {
            color: #e67e22;
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .hero-content .subtitle {
            font-size: 1rem;
            margin-bottom: 20px;
            letter-spacing: 3px;
        }

        /* Bouton */
        .btn-menu {
            display: inline-block;
            background: #e67e22;
            color: white;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: bold;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .btn-menu:hover {
            background: #d35400;
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        /* Navigation du slider */
        .slider-nav {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
            z-index: 10;
        }

        .slider-dot {
            width: 45px;
            height: 3px;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 0;
        }

        .slider-dot.active {
            background: #e67e22;
            width: 60px;
        }

        /* Flèches */
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0,0,0,0.5);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s;
            font-size: 1.5rem;
        }

        .slider-arrow:hover {
            background: #e67e22;
        }

        .slider-arrow.prev {
            left: 20px;
        }

        .slider-arrow.next {
            right: 20px;
        }

        /* ========== SECTION INFOS ========== */
        .info-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 60px 10%;
            background: #fff;
        }

        .info-card {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .info-card .icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .info-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .info-card p {
            color: #666;
            line-height: 1.6;
        }

        .info-card .phone {
            font-size: 1.5rem;
            color: #e67e22;
            font-weight: bold;
            text-decoration: none;
        }

        .info-card .hours {
            font-size: 1rem;
        }

        /* ========== SECTION À PROPOS ========== */
        .about-section {
            background: #f8f9fa;
            padding: 80px 10%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .about-content h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 20px;
        }

        .about-content h2 span {
            color: #e67e22;
        }

        .about-content p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .about-features {
            list-style: none;
            margin-top: 20px;
        }

        .about-features li {
            margin-bottom: 10px;
            color: #555;
        }

        .about-features li .check {
            color: #e67e22;
            margin-right: 10px;
        }

        .about-image {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .about-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .about-image:hover img {
            transform: scale(1.05);
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate(-50%, -40%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .info-section {
                grid-template-columns: 1fr;
                padding: 40px 20px;
            }
            
            .about-section {
                grid-template-columns: 1fr;
                padding: 40px 20px;
            }
            
            .btn-menu {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer a {
            color: #e67e22;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Hero Slider -->
    <div class="hero">
        <div class="slide slide1 active"></div>
        <div class="slide slide2"></div>
        <div class="slide slide3"></div>
        
        <div class="slider-arrow prev" onclick="changeSlide(-1)">❮</div>
        <div class="slider-arrow next" onclick="changeSlide(1)">❯</div>
        
        <div class="slider-nav">
            <div class="slider-dot active" onclick="currentSlide(0)"></div>
            <div class="slider-dot" onclick="currentSlide(1)"></div>
            <div class="slider-dot" onclick="currentSlide(2)"></div>
        </div>
        
        <div class="hero-content">
            <p class="subtitle">🇲🇦 CUISINE MAROCAINE AUTHENTIQUE 🇲🇦</p>
            <h1><span>Resto</span> Marocain Casa</h1>
            <p>Découvrez les saveurs authentiques du Maroc<br>dans une ambiance chaleureuse et conviviale</p>
            <a href="{{ route('menu.client') }}" class="btn-menu">🍽️ Découvrir notre menu</a>
        </div>
    </div>

    <!-- Section Infos pratiques -->
    <div class="info-section">
        <div class="info-card">
            <div class="icon">🍽️</div>
            <h3>Réservation</h3>
            <p>Réservez votre table en ligne ou par téléphone</p>
            <a href="tel:+212522123456" class="phone">📞 0522-123456</a>
        </div>
        
        <div class="info-card">
            <div class="icon">🕐</div>
            <h3>Horaires d'ouverture</h3>
            <p class="hours">
                <strong>Lundi - Samedi :</strong><br>
                12:00 - 15:00 | 19:00 - 23:00<br>
                <strong>Dimanche :</strong><br>
                12:00 - 16:00 (brunch)
            </p>
        </div>
        
        <div class="info-card">
            <div class="icon">📍</div>
            <h3>Notre adresse</h3>
            <p>123 Boulevard Mohammed V<br>Casablanca, Maroc<br>
            <a href="https://maps.google.com" style="color: #e67e22; text-decoration: none;">Voir sur Google Maps →</a></p>
        </div>
    </div>

    <!-- Section À propos -->
    <div class="about-section">
        <div class="about-content">
            <h2>Bienvenue chez <span>Resto Marocain Casa</span></h2>
            <p>Depuis plus de 15 ans, nous perpétuons la tradition culinaire marocaine avec passion et authenticité. Chaque plat est préparé avec des ingrédients frais et des épices soigneusement sélectionnées.</p>
            <p>Notre équipe vous accueille dans un cadre chaleureux pour vous faire découvrir ou redécouvrir les véritables saveurs du Maroc.</p>
            <ul class="about-features">
                <li>✓ Produits frais et locaux</li>
                <li>✓ Cuisine traditionnelle marocaine</li>
                <li>✓ Ambiance chaleureuse et conviviale</li>
                <li>✓ Service de traiteur sur demande</li>
            </ul>
        </div>
        <div class="about-image">
            <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?w=600" alt="Restaurant ambiance">
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© 2024 Resto Marocain Casa - Tous droits réservés</p>
        <p>Table 27 - Terrasse | Casablanca, Maroc</p>
        <p>🍽️ Pour passer commande : <a href="tel:+212522123456">0522-123456</a></p>
        <p>
            <a href="/menu-client" style="color: #e67e22;">Menu</a> | 
            <a href="/admin" style="color: #e67e22;">Espace Admin</a>
        </p>
    </div>

    <script>
        let slideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.slider-dot');
        
        function showSlide(n) {
            // Gérer l'index
            if (n >= slides.length) slideIndex = 0;
            if (n < 0) slideIndex = slides.length - 1;
            
            // Cacher tous les slides
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Afficher le slide actif
            slides[slideIndex].classList.add('active');
            dots[slideIndex].classList.add('active');
        }
        
        function changeSlide(direction) {
            slideIndex += direction;
            showSlide(slideIndex);
            resetTimer();
        }
        
        function currentSlide(n) {
            slideIndex = n;
            showSlide(slideIndex);
            resetTimer();
        }
        
        // Défilement automatique toutes les 5 secondes
        let autoTimer = setInterval(() => {
            slideIndex++;
            showSlide(slideIndex);
        }, 5000);
        
        function resetTimer() {
            clearInterval(autoTimer);
            autoTimer = setInterval(() => {
                slideIndex++;
                showSlide(slideIndex);
            }, 5000);
        }
        
        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.info-card, .about-section').forEach(el => {
            el.style.opacity = "0";
            el.style.transform = "translateY(30px)";
            el.style.transition = "all 0.6s ease-out";
            observer.observe(el);
        });
    </script>
</body>
</html>