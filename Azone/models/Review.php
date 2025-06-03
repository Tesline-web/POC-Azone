<?php
class Review {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $query = "INSERT INTO reviews (product_id, user_id, rating, comment) 
                 VALUES (:product_id, :user_id, :rating, :comment)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':product_id' => $data['product_id'],
            ':user_id' => $data['user_id'],
            ':rating' => $data['rating'],
            ':comment' => $data['comment']
        ]);
    }

    public function getByProductId($productId) {
        $query = "SELECT r.*, u.username 
                 FROM reviews r 
                 JOIN users u ON r.user_id = u.id 
                 WHERE r.product_id = :product_id 
                 ORDER BY r.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageRating($productId) {
        $query = "SELECT AVG(rating) as average, COUNT(*) as count 
                 FROM reviews 
                 WHERE product_id = :product_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function userHasReviewed($userId, $productId) {
        $query = "SELECT COUNT(*) as count 
                 FROM reviews 
                 WHERE user_id = :user_id AND product_id = :product_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId
        ]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    }

    public function update($reviewId, $userId, $data) {
        $query = "UPDATE reviews 
                 SET rating = :rating, comment = :comment 
                 WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $reviewId,
            ':user_id' => $userId,
            ':rating' => $data['rating'],
            ':comment' => $data['comment']
        ]);
    }

    public function delete($reviewId, $userId) {
        $query = "DELETE FROM reviews 
                 WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $reviewId,
            ':user_id' => $userId
        ]);
    }
}
