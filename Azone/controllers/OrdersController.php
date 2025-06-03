<?php
require_once MODELS_PATH . 'Order.php';
require_once MODELS_PATH . 'Cart.php';

class OrdersController extends BaseController {
    private $orderModel;
    private $cartModel;

    public function __construct() {
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
    }

    public function index() {
        $this->requireLogin();
        
        $orders = $this->orderModel->getUserOrders($_SESSION['user_id']);
        $this->render('orders/index', ['orders' => $orders]);
    }

    public function view($orderId) {
        $this->requireLogin();
        
        $order = $this->orderModel->getById($orderId, $_SESSION['user_id']);
        if (!$order) {
            $_SESSION['error'] = "Commande non trouvée";
            $this->redirect('orders');
            return;
        }
        
        $this->render('orders/view', ['order' => $order]);
    }

    public function create() {
        $this->requireLogin();
        
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        if (empty($cartItems)) {
            $_SESSION['error'] = "Votre panier est vide";
            $this->redirect('cart');
            return;
        }

        $total = $this->cartModel->getTotal($_SESSION['user_id']);
        $orderId = $this->orderModel->create($_SESSION['user_id'], $cartItems, $total);

        if ($orderId) {
            // Vider le panier après la commande
            $this->cartModel->clearCart($_SESSION['user_id']);
            $_SESSION['success'] = "Commande créée avec succès";
            $this->redirect('orders/view/' . $orderId);
        } else {
            $_SESSION['error'] = "Erreur lors de la création de la commande";
            $this->redirect('cart');
        }
    }

    public function success($orderId) {
        $this->requireLogin();
        
        $order = $this->orderModel->getById($orderId, $_SESSION['user_id']);
        if (!$order) {
            $this->redirect('orders');
            return;
        }

        $this->orderModel->updateStatus($orderId, 'paid');
        $this->render('orders/success', ['order' => $order]);
    }

    public function cancel($orderId) {
        $this->requireLogin();
        
        $order = $this->orderModel->getById($orderId, $_SESSION['user_id']);
        if (!$order || $order['status'] !== 'pending') {
            $_SESSION['error'] = "Impossible d'annuler cette commande";
            $this->redirect('orders');
            return;
        }

        if ($this->orderModel->updateStatus($orderId, 'cancelled')) {
            $_SESSION['success'] = "Commande annulée avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de l'annulation de la commande";
        }
        
        $this->redirect('orders');
    }
}
