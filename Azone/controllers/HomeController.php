<?php
require_once MODELS_PATH . 'Product.php';
require_once CONTROLLERS_PATH . 'BaseController.php';

class HomeController extends BaseController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        // Récupérer les produits récents
        $latestProducts = $this->productModel->getAll(null, 'created_at DESC', 8);
        
        $this->render('home/index', [
            'latestProducts' => $latestProducts
        ]);
    }
}
