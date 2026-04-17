<section class="mx-auto max-w-4xl space-y-6">
    <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">Decisions</p>
        <h1 class="mt-2 text-3xl font-bold text-slate-900">
            Decisions du chantier : <?php echo htmlspecialchars($project['title']); ?>
        </h1>
        <p class="mt-3 text-slate-600">Suivi des validations, rejets et annulations.</p>
    </div>

    <div class="flex flex-wrap items-center justify-center gap-3 md:justify-start">
        <a href="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>/decisions/create"
           class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
            Nouvelle decision
        </a>
        <a href="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>"
           class="rounded-xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700">
            Retour chantier
        </a>
    </div>

    <?php if (empty($decisions)): ?>
        <div class="rounded-2xl bg-white p-6 shadow ring-1 ring-slate-200">
            <p class="text-slate-600">Aucune decision pour ce chantier.</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($decisions as $decision): ?>
                <article class="rounded-2xl bg-white p-5 shadow ring-1 ring-slate-200">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">
                                <?php echo htmlspecialchars($decision['title']); ?>
                            </h2>
                            <p class="text-sm text-slate-500">
                                Creee le <?php echo htmlspecialchars($decision['created_at']); ?>
                            </p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                            <?php echo htmlspecialchars($decision['status']); ?>
                        </span>
                    </div>
                    <div class="mt-4">
                        <a href="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>/decisions/<?php echo (int) $decision['id']; ?>"
                           class="font-semibold text-blue-600 hover:underline">
                            Voir le detail
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
