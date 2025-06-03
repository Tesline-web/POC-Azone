<?php
class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($category = null, $sort = null, $limit = null, $offset = 0) {
        $query = "SELECT * FROM products";
        $params = [];
        
        if ($category) {
            $query .= " WHERE category = :category";
            $params[':category'] = $category;
        }
        
        if ($sort) {
            $query .= " ORDER BY " . $sort;
        }
        
        if ($limit) {
            $query .= " LIMIT :offset, :limit";
            $params[':offset'] = $offset;
            $params[':limit'] = $limit;
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO products (name, description, price, stock, image, category) 
                 VALUES (:name, :description, :price, :stock, :image, :category)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
            ':image' => $data['image'],
            ':category' => $data['category']
        ]);
    }

    public function update($id, $data) {
        $query = "UPDATE products 
                 SET name = :name, description = :description, price = :price, 
                     stock = :stock, image = :image, category = :category 
                 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
            ':image' => $data['image'],
            ':category' => $data['category']
        ]);
    }

    public function updateStock($id, $quantity) {
        $query = "UPDATE products SET stock = stock - :quantity WHERE id = :id AND stock >= :quantity";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':quantity' => $quantity
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
