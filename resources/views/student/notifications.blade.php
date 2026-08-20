@extends('layouts.base')

@section('titre', 'Mes notifications')

@section('contenu')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h1 class="h3 fw-bold mb-0">Mes notifications</h1>
        @if ($notifications->total() > 0)
            <form method="POST" action="{{ route('student.notifications.tout-lu') }}">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-outline-secondary btn-sm">Tout marquer comme lu</button>
            </form>
        @endif
    </div>

    @include('student.partials.nav')

    <div class="card border-0 shadow-sm">
        <div class="list-group list-group-flush">
            @forelse ($notifications as $notification)
                <div class="list-group-item @if(! $notification->lu) bg-light @endif">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">
                                @unless ($notification->lu)
                                    <span class="badge text-bg-primary me-1">Nouveau</span>
                                @endunless
                                {{ $notification->titre }}
                            </div>
                            <div class="small">{{ $notification->message }}</div>
                            <div class="small text-muted">{{ $notification->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        @unless ($notification->lu)
                            <form method="POST" action="{{ route('student.notifications.lue', $notification) }}">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-primary">Marquer comme lue</button>
                            </form>
                        @endunless
                    </div>
                </div>
            @empty
                <div class="list-group-item text-center text-muted py-4">Aucune notification pour le moment.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-3">{{ $notifications->links() }}</div>
@endsection
