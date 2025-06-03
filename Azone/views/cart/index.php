<div class="card">
    <div class="card-header">
        <h2 class="mb-0">Mon Panier</h2>
    </div>
    <div class="card-body">
        <?php if (empty($cartItems)): ?>
            <div class="alert alert-info">
                Votre panier est vide. <a href="<?= BASE_URL ?>/products">Continuer vos achats</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($item['image']): ?>
                                            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($item['image']) ?>" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                                 class="cart-item-image me-3">
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                        </div>
                                    </div>
                                </td>
                                <td><?= number_format($item['price'], 2) ?> €</td>
                                <td>
                                    <form action="<?= BASE_URL ?>/cart/update" method="POST" class="quantity-form">
                                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" 
                                               min="1" max="<?= $item['stock'] ?>" class="form-control quantity-input"
                                               onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td><?= number_format($item['price'] * $item['quantity'], 2) ?> €</td>
                                <td>
                                    <a href="<?= BASE_URL ?>/cart/remove/<?= $item['product_id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Êtes-vous sûr de vouloir retirer ce produit ?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                            <td><strong><?= number_format($total, 2) ?> €</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="<?= BASE_URL ?>/products" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Continuer les achats
                </a>
                <div>
                    <a href="<?= BASE_URL ?>/cart/clear" class="btn btn-warning me-2"
                       onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')">
                        <i class="fas fa-trash"></i> Vider le panier
                    </a>
                    <a href="<?= BASE_URL ?>/checkout" class="btn btn-success">
                        <i class="fas fa-shopping-cart"></i> Commander
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
