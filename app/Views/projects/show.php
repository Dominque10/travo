<section class="space-y-6">
    <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">
                    Fiche chantier
                </p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">
                    <?php echo $project['title']; ?>
                </h1>
                <p class="mt-4 max-w-3xl text-slate-600 leading-8">
                    <?php echo $project['description']; ?>
                </p>
            </div>

            <span class="inline-flex w-fit rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                <?php echo $project['status']; ?>
            </span>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
            <h2 class="text-xl font-bold text-slate-900">Progression</h2>

            <div class="mt-5 h-4 w-full rounded-full bg-slate-200">
                <div
                    class="h-4 rounded-full bg-blue-600"
                    style="width: <?php echo $project['progress']; ?>%;"
                ></div>
            </div>

            <p class="mt-3 text-sm text-slate-600">
                Avancement global : <strong><?php echo $project['progress']; ?>%</strong>
            </p>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-lg ring-1 ring-slate-200">
            <h2 class="text-xl font-bold text-slate-900">Informations</h2>

            <ul class="mt-4 space-y-3 text-slate-600">
                <li><strong>ID :</strong> <?php echo $project['id']; ?></li>
                <li><strong>Statut :</strong> <?php echo $project['status']; ?></li>
                <li><strong>Titre :</strong> <?php echo $project['title']; ?></li>
            </ul>
        </div>
    </div>

    <div class="flex flex-wrap gap-4">
        <a href="<?php echo BASE_URL; ?>/projects"
        class="inline-flex rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
            Retour à la liste
        </a>

        <a href="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>/edit"
        class="inline-flex rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600">
            Modifier le projet
        </a>

        <form action="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>/delete"
          method="POST"
          onsubmit="return confirm('Voulez-vous vraiment supprimer ce projet ?');">
            <button
                type="submit"
                class="inline-flex rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                Supprimer le projet
            </button>
        </form>
    </div>
</section>