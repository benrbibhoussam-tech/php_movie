<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    $stmt = $pdo->prepare("
        SELECT * FROM movies
        WHERE Title LIKE :search OR Director LIKE :search
        ORDER BY id DESC
        LIMIT 8
    ");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $pdo->query("
        SELECT * FROM movies
        ORDER BY id DESC
        LIMIT 8
    ");
}

$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinéVillage - Accueil</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="main-header">
    <div class="container header-container">
        <div class="logo">
            <h1>CinéVillage</h1>
        </div>

        <form action="index.php" method="GET" class="search-form">
            <input 
                type="text" 
                name="search" 
                placeholder="Rechercher par titre ou réalisateur..."
                value="<?= htmlspecialchars($search) ?>"
            >
            <button type="submit">Rechercher</button>
        </form>
    </div>
</header>

<main>
    <section class="hero">
        <div class="container">
            <h2>Blkujfilfllage</h2>
            <p>
                Découvrez nos derniers films ajoutés, recherchez par titre ou réalisateur
                et ajoutez vos films préférés au panier.
            </p>
        </div>
    </section>

    <section class="movies-section">
        <div class="container">
            <h3><?= !empty($search) ? "Résultats de recherche" : "Derniers films ajoutés" ?></h3>

            <div class="movies-grid">
                <?php if (!empty($movies)): ?>
                    <?php foreach ($movies as $movie): ?>
                        <div class="movie-card">
                            <div class="movie-image">
                                <?php if (!empty($movie['poster'])): ?>
                                    <img src="assets/images/<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['Title']) ?>">
                                <?php else: ?>
                                    <div class="no-image">Aucune image</div>
                                <?php endif; ?>
                            </div>

                            <div class="movie-info">
                                <h4><?= htmlspecialchars($movie['Title']) ?></h4>
                                <p class="director">Réalisateur : <?= htmlspecialchars($movie['Director']) ?></p>
                                <p class="price"><?= number_format((float)$movie['Price'], 2) ?> €</p>

                                <div class="movie-actions">
                                    <a href="movie.php?id=<?= $movie['id'] ?>" class="details-btn">Voir détails</a>
                                    <a href="cart.php?action=add&id=<?= $movie['id'] ?>" class="cart-btn">Ajouter au panier</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun film trouvé.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<footer class="main-footer">
    <a href="index.php">Accueil</a>
    <a href="category.php">Catégories</a>
    <a href="cart.php">Panier</a>
</footer>

</body>
</html>