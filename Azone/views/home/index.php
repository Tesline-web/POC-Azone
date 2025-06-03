<div class="hero-section text-center py-5 mb-4">
    <h1 class="display-4">Bienvenue sur Azone</h1>
    <p class="lead">Découvrez notre sélection de produits de qualité</p>
    <a href="<?= BASE_URL ?>/products" class="btn btn-primary btn-lg">
        Voir tous les produits
    </a>
</div>

<div class="featured-categories mb-5">
    <h2 class="text-center mb-4">Nos Catégories</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="category-card">
                <img src="<?= BASE_URL ?>/assets/images/electronics.jpg" alt="Électronique" class="img-fluid rounded">
                <div class="category-overlay">
                    <h3>Électronique</h3>
                    <a href="<?= BASE_URL ?>/products?category=electronics" class="btn btn-light">Découvrir</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="category-card">
                <img src="<?= BASE_URL ?>/assets/images/clothing.jpg" alt="Vêtements" class="img-fluid rounded">
                <div class="category-overlay">
                    <h3>Vêtements</h3>
                    <a href="<?= BASE_URL ?>/products?category=clothing" class="btn btn-light">Découvrir</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="category-card">
                <img src="<?= BASE_URL ?>/assets/images/books.jpg" alt="Livres" class="img-fluid rounded">
                <div class="category-overlay">
                    <h3>Livres</h3>
                    <a href="<?= BASE_URL ?>/products?category=books" class="btn btn-light">Découvrir</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="latest-products">
    <h2 class="text-center mb-4">Nouveaux Produits</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php foreach ($latestProducts as $product): ?>
            <div class="col">
                <div class="card h-100">
                    <?php if ($product['image']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($product['image']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php else: ?>
                        <img src="<?= BASE_URL ?>/assets/images/no-image.jpg" 
                             class="card-img-top" 
                             alt="No image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                        <p class="card-text"><strong><?= number_format($product['price'], 2) ?> €</strong></p>
                        <div class="d-grid gap-2">
                            <a href="<?= BASE_URL ?>/products/view/<?= $product['id'] ?>" 
                               class="btn btn-primary">Voir détails</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(rgba(52, 152, 219, 0.8), rgba(52, 152, 219, 0.9));
    color: white;
    border-radius: 10px;
    margin: 20px 0;
    padding: 60px 20px;
}

.category-card {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
}

.category-card img {
    transition: transform 0.3s ease;
}

.category-card:hover img {
    transform: scale(1.05);
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.category-card:hover .category-overlay {
    opacity: 1;
}

.category-overlay h3 {
    margin-bottom: 15px;
}
</style>
