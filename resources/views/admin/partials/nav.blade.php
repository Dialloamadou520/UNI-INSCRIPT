<ul class="nav nav-pills mb-4 gap-1 flex-wrap">
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
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('admin.etudiants.index') || request()->routeIs('admin.etudiants.create') || request()->routeIs('admin.etudiants.edit')) active @endif"
           href="{{ route('admin.etudiants.index') }}">
            <i class="bi bi-people me-1"></i> Étudiants
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('admin.etudiants.import')) active @endif" href="{{ route('admin.etudiants.import') }}">
            <i class="bi bi-upload me-1"></i> Importation
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('admin.filieres.*')) active @endif" href="{{ route('admin.filieres.index') }}">
            <i class="bi bi-diagram-3 me-1"></i> Filières
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('admin.niveaux.*')) active @endif" href="{{ route('admin.niveaux.index') }}">
            <i class="bi bi-bar-chart-steps me-1"></i> Niveaux
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('admin.annees.*')) active @endif" href="{{ route('admin.annees.index') }}">
            <i class="bi bi-calendar3 me-1"></i> Années académiques
        </a>
    </li>
</ul>
