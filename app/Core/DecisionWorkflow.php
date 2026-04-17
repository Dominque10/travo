<?php

class DecisionWorkflow
{
    private PDO $pdo;
    private Decision $decisionModel;
    private DecisionLog $decisionLogModel;

    private const ALLOWED_TRANSITIONS = [
        'draft' => ['pending', 'cancelled'],
        'pending' => ['approved', 'rejected', 'cancelled'],
        'rejected' => ['draft'],
        'approved' => [],
        'cancelled' => [],
    ];

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->decisionModel = new Decision();
        $this->decisionLogModel = new DecisionLog();
    }

    public function canTransition(string $fromStatus, string $toStatus): bool
    {
        return in_array($toStatus, self::ALLOWED_TRANSITIONS[$fromStatus] ?? [], true);
    }

    public function getAvailableActions(string $status): array
    {
        return self::ALLOWED_TRANSITIONS[$status] ?? [];
    }

    public function transition(array $decision, int $userId, string $toStatus, string $message, ?string $responseComment = null): bool
    {
        $fromStatus = (string) $decision['status'];

        if (!$this->canTransition($fromStatus, $toStatus)) {
            return false;
        }

        $validatedAt = $toStatus === 'approved'
            ? date('Y-m-d H:i:s')
            : null;

        try {
            $this->pdo->beginTransaction();

            $this->decisionModel->updateStatus(
                (int) $decision['id'],
                $toStatus,
                $responseComment,
                $validatedAt
            );

            $this->decisionLogModel->create([
                'decision_id' => (int) $decision['id'],
                'user_id' => $userId,
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'message' => $message,
            ]);

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return false;
        }
    }
}
