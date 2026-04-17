<?php
$statusLabels = [
    'draft' => 'Brouillon',
    'pending' => 'En attente',
    'approved' => 'Approuvee',
    'rejected' => 'Rejetee',
    'cancelled' => 'Annulee',
];
?>

<section class="mx-auto max-w-4xl space-y-6">
    <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">Decision</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900"><?php echo htmlspecialchars($decision['title']); ?></h1>
                <p class="mt-3 text-slate-600"><?php echo nl2br(htmlspecialchars($decision['description'])); ?></p>
            </div>
            <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                <?php echo htmlspecialchars($statusLabels[$decision['status']] ?? $decision['status']); ?>
            </span>
        </div>

        <div class="mt-4 text-sm text-slate-500">
            Creee le <?php echo htmlspecialchars($decision['created_at']); ?>
            <?php if (!empty($decision['validated_at'])): ?>
                - validee le <?php echo htmlspecialchars($decision['validated_at']); ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($decision['response_comment'])): ?>
            <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-700">Commentaire de reponse</p>
                <p class="mt-2 text-slate-600"><?php echo nl2br(htmlspecialchars($decision['response_comment'])); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ((string) $decision['status'] !== 'approved'): ?>
        <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
            <h2 class="text-xl font-bold text-slate-900">Modifier le contenu</h2>
            <form action="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>/decisions/<?php echo (int) $decision['id']; ?>/update"
                  method="POST"
                  class="mt-6 space-y-4">
                <div>
                    <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Titre</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($decision['title']); ?>"
                           class="w-full rounded-xl border border-slate-300 px-4 py-3">
                </div>
                <div>
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Description</label>
                    <textarea id="description" name="description" rows="5"
                              class="w-full rounded-xl border border-slate-300 px-4 py-3"><?php echo htmlspecialchars($decision['description']); ?></textarea>
                </div>
                <button type="submit"
                        class="rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white hover:bg-amber-600">
                    Enregistrer les modifications
                </button>
            </form>
        </div>
    <?php endif; ?>

    <?php if (!empty($actions)): ?>
        <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
            <h2 class="text-xl font-bold text-slate-900">Actions disponibles</h2>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <?php foreach ($actions as $action): ?>
                    <form action="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>/decisions/<?php echo (int) $decision['id']; ?>/transition"
                          method="POST"
                          class="rounded-2xl border border-slate-200 p-4 space-y-2">
                        <input type="hidden" name="to_status" value="<?php echo htmlspecialchars($action); ?>">
                        <?php if (in_array($action, ['approved', 'rejected', 'cancelled'], true)): ?>
                            <textarea name="response_comment"
                                      rows="2"
                                      placeholder="Commentaire (optionnel)"
                                      class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                        <?php endif; ?>
                        <button type="submit"
                                class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                            Passer a "<?php echo htmlspecialchars($action); ?>"
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <h2 class="text-xl font-bold text-slate-900">Historique des transitions</h2>
        <?php if (empty($logs)): ?>
            <p class="mt-4 text-slate-600">Aucun log pour cette decision.</p>
        <?php else: ?>
            <div class="mt-4 space-y-3">
                <?php foreach ($logs as $log): ?>
                    <article class="rounded-xl border border-slate-200 p-4">
                        <p class="font-semibold text-slate-800">
                            <?php echo htmlspecialchars($log['from_status']); ?> -> <?php echo htmlspecialchars($log['to_status']); ?>
                        </p>
                        <p class="text-sm text-slate-600"><?php echo htmlspecialchars($log['message']); ?></p>
                        <p class="mt-1 text-xs text-slate-500">
                            Par <?php echo htmlspecialchars($log['user_name']); ?> - <?php echo htmlspecialchars($log['created_at']); ?>
                        </p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="flex flex-wrap items-center justify-center gap-3 md:justify-start">
        <a href="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>/decisions"
           class="rounded-xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700">
            Retour decisions
        </a>
        <a href="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>"
           class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Retour chantier
        </a>
    </div>
</section>
