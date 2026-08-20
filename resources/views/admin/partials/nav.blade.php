<ul class="nav nav-pills mb-4 gap-1">
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2 me-1"></i> Statistiques
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('admin.inscriptions.*')) active @endif" href="{{ route('admin.inscriptions.index') }}">
            <i class="bi bi-inboxes me-1"></i> Inscriptions
        </a>
    </li>
</ul>
