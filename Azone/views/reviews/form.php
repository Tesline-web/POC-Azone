<div class="card">
    <div class="card-header">
        <h2 class="mb-0"><?= $action === 'add' ? 'Ajouter un avis' : 'Modifier votre avis' ?></h2>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="product-info">
                    <h4><?= htmlspecialchars($product['name']) ?></h4>
                    <?php if ($product['image']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="img-thumbnail" style="max-width: 200px;">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <form action="<?= BASE_URL ?>/reviews/<?= $action ?><?= $action === 'edit' ? '/' . $review['id'] : '' ?>" 
              method="POST" class="review-form">
            
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            
            <div class="mb-3">
                <label class="form-label">Note</label>
                <div class="rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" name="rating" value="<?= $i ?>" 
                               id="star<?= $i ?>" required
                               <?= isset($review) && $review['rating'] == $i ? 'checked' : '' ?>>
                        <label for="star<?= $i ?>">☆</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="comment" class="form-label">Votre avis</label>
                <textarea name="comment" id="comment" rows="4" 
                          class="form-control" required><?= isset($review) ? htmlspecialchars($review['comment']) : '' ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/products/view/<?= $product['id'] ?>" class="btn btn-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <?= $action === 'add' ? 'Publier' : 'Mettre à jour' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    font-size: 30px;
    color: #ddd;
    padding: 5px;
}

.rating label:before {
    content: '★';
}

.rating input:checked ~ label {
    color: #ffd700;
}

.rating label:hover,
.rating label:hover ~ label {
    color: #ffd700;
}

.rating input:checked + label:hover,
.rating input:checked ~ label:hover,
.rating label:hover ~ input:checked ~ label,
.rating input:checked ~ label:hover ~ label {
    color: #ffed4a;
}
</style>
