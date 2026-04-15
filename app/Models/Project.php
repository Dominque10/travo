<?php 

class Project {
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }
    
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM projects");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}