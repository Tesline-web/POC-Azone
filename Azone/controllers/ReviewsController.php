<?php
require_once MODELS_PATH . 'Review.php';
require_once MODELS_PATH . 'Product.php';

class ReviewsController extends BaseController {
    private $reviewModel;
    private $productModel;

    public function __construct() {
        $this->reviewModel = new Review();
        $this->productModel = new Product();
    }

    public function add($productId) {
        $this->requireLogin();
        
        $product = $this->productModel->getById($productId);
        if (!$product) {
            $_SESSION['error'] = "Produit non trouvé";
            $this->redirect('products');
            return;
        }

        if ($this->reviewModel->userHasReviewed($_SESSION['user_id'], $productId)) {
            $_SESSION['error'] = "Vous avez déjà évalué ce produit";
            $this->redirect('products/view/' . $productId);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = (int)($_POST['rating'] ?? 0);
            $comment = $_POST['comment'] ?? '';

            if ($rating < 1 || $rating > 5) {
                $_SESSION['error'] = "La note doit être comprise entre 1 et 5";
            } else {
                $data = [
                    'product_id' => $productId,
                    'user_id' => $_SESSION['user_id'],
                    'rating' => $rating,
                    'comment' => $comment
                ];

                if ($this->reviewModel->create($data)) {
                    $_SESSION['success'] = "Merci pour votre avis !";
                    $this->redirect('products/view/' . $productId);
                    return;
                } else {
                    $_SESSION['error'] = "Erreur lors de l'ajout de l'avis";
                }
            }
        }

        $this->render('reviews/form', [
            'product' => $product,
            'action' => 'add'
        ]);
    }

    public function edit($reviewId) {
        $this->requireLogin();
        
        // Logique pour éditer un avis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = (int)($_POST['rating'] ?? 0);
            $comment = $_POST['comment'] ?? '';

            if ($rating < 1 || $rating > 5) {
                $_SESSION['error'] = "La note doit être comprise entre 1 et 5";
            } else {
                $data = [
                    'rating' => $rating,
                    'comment' => $comment
                ];

                if ($this->reviewModel->update($reviewId, $_SESSION['user_id'], $data)) {
                    $_SESSION['success'] = "Avis mis à jour avec succès";
                    $this->redirect('products/view/' . $_POST['product_id']);
                    return;
                } else {
                    $_SESSION['error'] = "Erreur lors de la mise à jour de l'avis";
                }
            }
        }
    }

    public function delete($reviewId) {
        $this->requireLogin();
        
        if ($this->reviewModel->delete($reviewId, $_SESSION['user_id'])) {
            $_SESSION['success'] = "Avis supprimé avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression de l'avis";
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? 'products');
    }
}
