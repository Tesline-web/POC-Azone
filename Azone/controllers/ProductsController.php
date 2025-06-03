<?php
require_once MODELS_PATH . 'Product.php';

class ProductsController extends BaseController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        $category = $_GET['category'] ?? null;
        $sort = $_GET['sort'] ?? null;
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $products = $this->productModel->getAll($category, $sort, $limit, $offset);
        $this->render('products/index', [
            'products' => $products,
            'currentCategory' => $category,
            'currentSort' => $sort,
            'currentPage' => $page
        ]);
    }

    public function view($id) {
        $product = $this->productModel->getById($id);
        if (!$product) {
            $this->redirect('products');
        }
        $this->render('products/view', ['product' => $product]);
    }

    public function add() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'stock' => $_POST['stock'] ?? 0,
                'category' => $_POST['category'] ?? '',
                'image' => ''
            ];

            // Gestion de l'upload d'image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $uploadDir = UPLOADS_PATH . 'products/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid() . '_' . $_FILES['image']['name'];
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $data['image'] = 'uploads/products/' . $fileName;
                }
            }

            if ($this->productModel->create($data)) {
                $_SESSION['success'] = "Produit ajouté avec succès";
                $this->redirect('products');
            } else {
                $this->render('products/add', [
                    'error' => "Erreur lors de l'ajout du produit",
                    'data' => $data
                ]);
            }
        } else {
            $this->render('products/add');
        }
    }

    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('users/login');
        }

        $product = $this->productModel->getById($id);
        if (!$product) {
            $this->redirect('products');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? $product['name'],
                'description' => $_POST['description'] ?? $product['description'],
                'price' => $_POST['price'] ?? $product['price'],
                'stock' => $_POST['stock'] ?? $product['stock'],
                'category' => $_POST['category'] ?? $product['category'],
                'image' => $product['image']
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $uploadDir = UPLOADS_PATH . 'products/';
                $fileName = uniqid() . '_' . $_FILES['image']['name'];
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    if ($product['image'] && file_exists(ROOT_PATH . $product['image'])) {
                        unlink(ROOT_PATH . $product['image']);
                    }
                    $data['image'] = 'uploads/products/' . $fileName;
                }
            }

            if ($this->productModel->update($id, $data)) {
                $_SESSION['success'] = "Produit mis à jour avec succès";
                $this->redirect('products/view/' . $id);
            } else {
                $this->render('products/edit', [
                    'error' => "Erreur lors de la mise à jour du produit",
                    'product' => $product
                ]);
            }
        } else {
            $this->render('products/edit', ['product' => $product]);
        }
    }

    public function delete($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('users/login');
        }

        $product = $this->productModel->getById($id);
        if ($product && $this->productModel->delete($id)) {
            if ($product['image'] && file_exists(ROOT_PATH . $product['image'])) {
                unlink(ROOT_PATH . $product['image']);
            }
            $_SESSION['success'] = "Produit supprimé avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du produit";
        }
        $this->redirect('products');
    }
}
