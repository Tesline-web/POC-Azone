<div class="card">
    <div class="card-header">
        <h2 class="mb-0">Mes Commandes</h2>
    </div>
    <div class="card-body">
        <?php if (empty($orders)): ?>
            <div class="alert alert-info">
                Vous n'avez pas encore de commandes. <a href="<?= BASE_URL ?>/products">Commencer vos achats</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td>
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
                                </td>
                                <td><?= number_format($order['total_amount'], 2) ?> €</td>
                                <td>
                                    <a href="<?= BASE_URL ?>/orders/view/<?= $order['id'] ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <a href="<?= BASE_URL ?>/orders/cancel/<?= $order['id'] ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                            <i class="fas fa-times"></i> Annuler
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
