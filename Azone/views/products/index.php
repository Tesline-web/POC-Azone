<div class="row mb-4">
    <div class="col-md-4">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown">
                <?= $currentCategory ? ucfirst($currentCategory) : 'Toutes les catégories' ?>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= BASE_URL ?>/products">Toutes les catégories</a></li>
                <li><a class="dropdown-item" href="<?= BASE_URL ?>/products?category=electronics">Électronique</a></li>
                <li><a class="dropdown-item" href="<?= BASE_URL ?>/products?category=clothing">Vêtements</a></li>
                <li><a class="dropdown-item" href="<?= BASE_URL ?>/products?category=books">Livres</a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                Trier par
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= BASE_URL ?>/products?sort=price_asc">Prix croissant</a></li>
                <li><a class="dropdown-item" href="<?= BASE_URL ?>/products?sort=price_desc">Prix décroissant</a></li>
                <li><a class="dropdown-item" href="<?= BASE_URL ?>/products?sort=name_asc">Nom A-Z</a></li>
                <li><a class="dropdown-item" href="<?= BASE_URL ?>/products?sort=name_desc">Nom Z-A</a></li>
            </ul>
        </div>
    </div>
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="col-md-4 text-end">
        <a href="<?= BASE_URL ?>/products/add" class="btn btn-primary">Ajouter un produit</a>
    </div>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
    <?php foreach ($products as $product): ?>
        <div class="col">
            <div class="card h-100">
                <?php if ($product['image']): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                <?php else: ?>
                    <img src="<?= BASE_URL ?>/assets/images/no-image.jpg" class="card-img-top" alt="No image">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                    <p class="card-text"><strong><?= number_format($product['price'], 2) ?> €</strong></p>
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>/products/view/<?= $product['id'] ?>" class="btn btn-primary">Voir détails</a>
                        <?php if ($product['stock'] > 0): ?>
                            <a href="<?= BASE_URL ?>/cart/add/<?= $product['id'] ?>" class="btn btn-success">Ajouter au panier</a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>Rupture de stock</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info">
        Aucun produit trouvé.
    </div>
<?php endif; ?>
