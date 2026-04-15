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

    public function create(array $data): bool
    {
        $sql = "INSERT INTO projects (title, status, description, progress)
                VALUES (:title, :status, :description, :progress)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'title' => $data['title'],
            'status' => $data['status'],
            'description' => $data['description'],
            'progress' => $data['progress']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE projects
                SET title = :title,
                    status = :status,
                    description = :description,
                    progress = :progress
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'status' => $data['status'],
            'description' => $data['description'],
            'progress' => $data['progress']
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM projects WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id
        ]);
    }
}