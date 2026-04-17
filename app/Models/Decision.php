<?php

class Decision
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO decisions (project_id, user_id, title, description, status)
                VALUES (:project_id, :user_id, :title, :description, :status)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'project_id' => $data['project_id'],
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'] ?? 'draft',
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function getByProjectIdForUser(int $projectId, int $userId): array
    {
        $sql = "SELECT d.*
                FROM decisions d
                INNER JOIN projects p ON p.id = d.project_id
                WHERE d.project_id = :project_id
                AND p.user_id = :user_id
                ORDER BY d.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);

        return $stmt->fetchAll();
    }

    public function getByIdForProjectAndUser(int $decisionId, int $projectId, int $userId): ?array
    {
        $sql = "SELECT d.*
                FROM decisions d
                INNER JOIN projects p ON p.id = d.project_id
                WHERE d.id = :decision_id
                AND d.project_id = :project_id
                AND p.user_id = :user_id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'decision_id' => $decisionId,
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);

        $decision = $stmt->fetch();
        return $decision ?: null;
    }

    public function updateContent(int $decisionId, string $title, string $description): bool
    {
        $sql = "UPDATE decisions
                SET title = :title,
                    description = :description
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $decisionId,
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function updateStatus(int $decisionId, string $toStatus, ?string $responseComment, ?string $validatedAt): bool
    {
        $sql = "UPDATE decisions
                SET status = :status,
                    response_comment = :response_comment,
                    validated_at = :validated_at
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $decisionId,
            'status' => $toStatus,
            'response_comment' => $responseComment,
            'validated_at' => $validatedAt,
        ]);
    }
}
