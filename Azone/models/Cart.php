<?php
class Cart {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addItem($userId, $productId, $quantity = 1) {
        // Vérifier si le produit existe déjà dans le panier
        $query = "SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId
        ]);
        
        if ($stmt->fetch()) {
            // Mettre à jour la quantité
            $query = "UPDATE cart SET quantity = quantity + :quantity 
                     WHERE user_id = :user_id AND product_id = :product_id";
        } else {
            // Ajouter un nouveau produit
            $query = "INSERT INTO cart (user_id, product_id, quantity) 
                     VALUES (:user_id, :product_id, :quantity)";
        }

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':quantity' => $quantity
        ]);
    }

    public function updateQuantity($userId, $productId, $quantity) {
        $query = "UPDATE cart SET quantity = :quantity 
                 WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':quantity' => $quantity
        ]);
    }

    public function removeItem($userId, $productId) {
        $query = "DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId
        ]);
    }

    public function getCartItems($userId) {
        $query = "SELECT c.*, p.name, p.price, p.image, p.stock 
                 FROM cart c 
                 JOIN products p ON c.product_id = p.id 
                 WHERE c.user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal($userId) {
        $query = "SELECT SUM(c.quantity * p.price) as total 
                 FROM cart c 
                 JOIN products p ON c.product_id = p.id 
                 WHERE c.user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function clearCart($userId) {
        $query = "DELETE FROM cart WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':user_id' => $userId]);
    }
}
