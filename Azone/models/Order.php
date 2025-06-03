<?php
class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $cartItems, $totalAmount) {
        try {
            $this->db->beginTransaction();

            // Créer la commande
            $query = "INSERT INTO orders (user_id, total_amount, status) VALUES (:user_id, :total_amount, 'pending')";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':user_id' => $userId,
                ':total_amount' => $totalAmount
            ]);
            
            $orderId = $this->db->lastInsertId();

            // Ajouter les produits de la commande
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                     VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $this->db->prepare($query);

            foreach ($cartItems as $item) {
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['product_id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);

                // Mettre à jour le stock
                $updateStock = "UPDATE products 
                              SET stock = stock - :quantity 
                              WHERE id = :product_id AND stock >= :quantity";
                $stmtStock = $this->db->prepare($updateStock);
                $stmtStock->execute([
                    ':product_id' => $item['product_id'],
                    ':quantity' => $item['quantity']
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getById($orderId, $userId = null) {
        $query = "SELECT o.*, u.username 
                 FROM orders o 
                 JOIN users u ON o.user_id = u.id 
                 WHERE o.id = :id";
        $params = [':id' => $orderId];
        
        if ($userId !== null) {
            $query .= " AND o.user_id = :user_id";
            $params[':user_id'] = $userId;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($order) {
            $order['items'] = $this->getOrderItems($orderId);
        }
        
        return $order;
    }

    public function getOrderItems($orderId) {
        $query = "SELECT oi.*, p.name, p.image 
                 FROM order_items oi 
                 JOIN products p ON oi.product_id = p.id 
                 WHERE oi.order_id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserOrders($userId) {
        $query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($orders as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }
        
        return $orders;
    }

    public function updateStatus($orderId, $status) {
        $query = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $orderId,
            ':status' => $status
        ]);
    }
}
