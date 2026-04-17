<?php

class DecisionLog
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO decision_logs (decision_id, user_id, from_status, to_status, message)
                VALUES (:decision_id, :user_id, :from_status, :to_status, :message)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'decision_id' => $data['decision_id'],
            'user_id' => $data['user_id'],
            'from_status' => $data['from_status'],
            'to_status' => $data['to_status'],
            'message' => $data['message'],
        ]);
    }

    public function getByDecisionId(int $decisionId): array
    {
        $sql = "SELECT dl.*, u.name AS user_name
                FROM decision_logs dl
                INNER JOIN users u ON u.id = dl.user_id
                WHERE dl.decision_id = :decision_id
                ORDER BY dl.created_at ASC, dl.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'decision_id' => $decisionId,
        ]);

        return $stmt->fetchAll();
    }
}
