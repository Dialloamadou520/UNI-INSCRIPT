<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h6 text-muted text-uppercase mb-3">Historique des actions</h2>
        @if ($registration === null || $registration->histories->isEmpty())
            <p class="text-muted mb-0">Aucune action enregistrée pour le moment.</p>
        @else
            <ul class="list-group list-group-flush">
                @foreach ($registration->histories->sortByDesc('created_at') as $historique)
                    <li class="list-group-item px-0">
                        <div class="d-flex justify-content-between gap-2">
                            <span class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $historique->action) }}</span>
                            <span class="text-muted small">{{ $historique->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if ($historique->nouveau_statut)
                            <div class="small text-muted">
                                Statut : {{ \App\Models\Registration::STATUTS[$historique->nouveau_statut] ?? $historique->nouveau_statut }}
                            </div>
                        @endif
                        @if ($historique->commentaire)
                            <div class="small">{{ $historique->commentaire }}</div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
