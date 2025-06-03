<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Commande #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></h2>
            <a href="<?= BASE_URL ?>/orders" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux commandes
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Détails de la commande</h5>
                <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                <p>
                    <strong>Statut :</strong>
                    <?php
                    $statusClass = [
                        'pending' => 'warning',
                        'paid' => 'success',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    ];
                    $statusText = [
                        'pending' => 'En attente',
                        'paid' => 'Payée',
                        'shipped' => 'Expédiée',
                        'delivered' => 'Livrée',
                        'cancelled' => 'Annulée'
                    ];
                    ?>
                    <span class="badge bg-<?= $statusClass[$order['status']] ?>">
                        <?= $statusText[$order['status']] ?>
                    </span>
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5>Total</h5>
                <h3 class="text-primary"><?= number_format($order['total_amount'], 2) ?> €</h3>
            </div>
        </div>

        <h5 class="mb-3">Articles commandés</h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
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
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['price'] * $item['quantity'], 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                        <td><strong><?= number_format($order['total_amount'], 2) ?> €</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php if ($order['status'] === 'pending'): ?>
            <div class="mt-4">
                <a href="<?= BASE_URL ?>/orders/cancel/<?= $order['id'] ?>" 
                   class="btn btn-danger"
                   onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                    <i class="fas fa-times"></i> Annuler la commande
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
