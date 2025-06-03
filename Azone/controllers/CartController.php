<?php
require_once MODELS_PATH . 'Cart.php';
require_once MODELS_PATH . 'Product.php';

class CartController extends BaseController {
    private $cartModel;
    private $productModel;

    public function __construct() {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    public function index() {
        $this->requireLogin();
        
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        $total = $this->cartModel->getTotal($_SESSION['user_id']);
        
        $this->render('cart/index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function add($productId) {
        $this->requireLogin();
        
        $product = $this->productModel->getById($productId);
        if (!$product || $product['stock'] <= 0) {
            $_SESSION['error'] = "Produit non disponible";
            $this->redirect('products');
            return;
        }

        if ($this->cartModel->addItem($_SESSION['user_id'], $productId)) {
            $_SESSION['success'] = "Produit ajouté au panier";
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout au panier";
        }

        $this->redirect('cart');
    }

    public function update() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $quantity = (int)($_POST['quantity'] ?? 0);

            if ($productId && $quantity > 0) {
                $product = $this->productModel->getById($productId);
                if ($product && $quantity <= $product['stock']) {
                    if ($this->cartModel->updateQuantity($_SESSION['user_id'], $productId, $quantity)) {
                        $_SESSION['success'] = "Quantité mise à jour";
                    } else {
                        $_SESSION['error'] = "Erreur lors de la mise à jour";
                    }
                } else {
                    $_SESSION['error'] = "Quantité non disponible";
                }
            }
        }
        
        $this->redirect('cart');
    }

    public function remove($productId) {
        $this->requireLogin();
        
        if ($this->cartModel->removeItem($_SESSION['user_id'], $productId)) {
            $_SESSION['success'] = "Produit retiré du panier";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression";
        }
        
        $this->redirect('cart');
    }

    public function clear() {
        $this->requireLogin();
        
        if ($this->cartModel->clearCart($_SESSION['user_id'])) {
            $_SESSION['success'] = "Panier vidé avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du panier";
        }
        
        $this->redirect('cart');
    }
}
