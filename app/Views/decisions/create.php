<section class="mx-auto w-full max-w-3xl">
    <div class="rounded-3xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">Nouvelle decision</p>
        <h1 class="mt-2 text-3xl font-bold text-slate-900">Creer une decision</h1>
        <p class="mt-3 text-slate-600">
            Chantier : <?php echo htmlspecialchars($project['title']); ?>.
            La decision sera creee a l'etat draft.
        </p>

        <form action="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>/decisions/store"
              method="POST"
              class="mt-8 space-y-6">
            <div>
                <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Titre</label>
                <input type="text"
                       id="title"
                       name="title"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3"
                       placeholder="Exemple : Validation carrelage cuisine">
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Description</label>
                <textarea id="description"
                          name="description"
                          rows="6"
                          class="w-full rounded-xl border border-slate-300 px-4 py-3"
                          placeholder="Contexte, options, impact budget..."></textarea>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3 md:justify-start">
                <button type="submit"
                        class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    Enregistrer
                </button>
                <a href="<?php echo BASE_URL; ?>/projects/<?php echo (int) $project['id']; ?>/decisions"
                   class="rounded-xl border border-slate-300 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</section>
