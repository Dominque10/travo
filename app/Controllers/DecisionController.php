<?php

class DecisionController extends Controller
{
    private Project $projectModel;
    private Decision $decisionModel;
    private DecisionLog $decisionLogModel;
    private DecisionWorkflow $workflow;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->decisionModel = new Decision();
        $this->decisionLogModel = new DecisionLog();
        $this->workflow = new DecisionWorkflow();
    }

    public function index($projectId): void
    {
        $userId = $this->requireAuth();
        $project = $this->projectModel->getByIdForUser((int) $projectId, $userId);

        if (!$project) {
            $this->notFound();
        }

        $decisions = $this->decisionModel->getByProjectIdForUser((int) $projectId, $userId);

        $this->view('decisions/index', [
            'project' => $project,
            'decisions' => $decisions,
        ]);
    }

    public function create($projectId): void
    {
        $userId = $this->requireAuth();
        $project = $this->projectModel->getByIdForUser((int) $projectId, $userId);

        if (!$project) {
            $this->notFound();
        }

        $this->view('decisions/create', [
            'project' => $project,
        ]);
    }

    public function store($projectId): void
    {
        $userId = $this->requireAuth();
        $project = $this->projectModel->getByIdForUser((int) $projectId, $userId);

        if (!$project) {
            $this->notFound();
        }

        $validator = Validator::make($_POST, [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            Notification::setFlash('error', $validator->firstError() ?? 'Formulaire invalide.');
            $this->redirect('/projects/' . (int) $projectId . '/decisions/create');
        }

        $data = $validator->validated();

        $decisionId = $this->decisionModel->create([
            'project_id' => (int) $projectId,
            'user_id' => $userId,
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => 'draft',
        ]);

        $this->decisionLogModel->create([
            'decision_id' => $decisionId,
            'user_id' => $userId,
            'from_status' => 'draft',
            'to_status' => 'draft',
            'message' => 'Décision créée en brouillon.',
        ]);

        Notification::setFlash('success', 'Décision créée en brouillon.');
        $this->redirect('/projects/' . (int) $projectId . '/decisions/' . $decisionId);
    }

    public function show($projectId, $decisionId): void
    {
        $userId = $this->requireAuth();
        $project = $this->projectModel->getByIdForUser((int) $projectId, $userId);

        if (!$project) {
            $this->notFound();
        }

        $decision = $this->decisionModel->getByIdForProjectAndUser((int) $decisionId, (int) $projectId, $userId);

        if (!$decision) {
            $this->notFound();
        }

        $logs = $this->decisionLogModel->getByDecisionId((int) $decisionId);
        $actions = $this->workflow->getAvailableActions((string) $decision['status']);

        $this->view('decisions/show', [
            'project' => $project,
            'decision' => $decision,
            'logs' => $logs,
            'actions' => $actions,
        ]);
    }

    public function update($projectId, $decisionId): void
    {
        $userId = $this->requireAuth();
        $decision = $this->decisionModel->getByIdForProjectAndUser((int) $decisionId, (int) $projectId, $userId);

        if (!$decision) {
            $this->notFound();
        }

        if ((string) $decision['status'] === 'approved') {
            Notification::setFlash('error', 'Une décision approuvée ne peut plus être modifiée.');
            $this->redirect('/projects/' . (int) $projectId . '/decisions/' . (int) $decisionId);
        }

        $validator = Validator::make($_POST, [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            Notification::setFlash('error', $validator->firstError() ?? 'Formulaire invalide.');
            $this->redirect('/projects/' . (int) $projectId . '/decisions/' . (int) $decisionId);
        }

        $data = $validator->validated();
        $this->decisionModel->updateContent((int) $decisionId, $data['title'], $data['description']);

        Notification::setFlash('success', 'Décision mise à jour.');
        $this->redirect('/projects/' . (int) $projectId . '/decisions/' . (int) $decisionId);
    }

    public function transition($projectId, $decisionId): void
    {
        $userId = $this->requireAuth();
        $decision = $this->decisionModel->getByIdForProjectAndUser((int) $decisionId, (int) $projectId, $userId);

        if (!$decision) {
            $this->notFound();
        }

        $toStatus = trim($_POST['to_status'] ?? '');
        $responseComment = trim($_POST['response_comment'] ?? '');
        $responseComment = $responseComment === '' ? null : $responseComment;

        if ($toStatus === '') {
            Notification::setFlash('error', 'Transition invalide.');
            $this->redirect('/projects/' . (int) $projectId . '/decisions/' . (int) $decisionId);
        }

        $message = $this->buildMessage($toStatus);

        $ok = $this->workflow->transition(
            $decision,
            $userId,
            $toStatus,
            $message,
            $responseComment
        );

        if (!$ok) {
            Notification::setFlash('error', 'Transition non autorisée ou erreur technique.');
            $this->redirect('/projects/' . (int) $projectId . '/decisions/' . (int) $decisionId);
        }

        Notification::setFlash('success', 'Statut de la décision mis à jour.');
        $this->redirect('/projects/' . (int) $projectId . '/decisions/' . (int) $decisionId);
    }

    private function buildMessage(string $toStatus): string
    {
        switch ($toStatus) {
            case 'pending':
                return 'Décision envoyée en validation.';
            case 'approved':
                return 'Décision approuvée.';
            case 'rejected':
                return 'Décision rejetée.';
            case 'cancelled':
                return 'Décision annulée.';
            case 'draft':
                return 'Décision repassée en brouillon.';
            default:
                return 'Transition de décision.';
        }
    }
}
