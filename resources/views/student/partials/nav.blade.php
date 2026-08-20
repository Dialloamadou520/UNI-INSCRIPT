@php($notificationsNonLues = auth()->user()->notificationsInternes()->where('lu', false)->count())

<ul class="nav nav-pills mb-4 gap-1">
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('student.dashboard')) active @endif" href="{{ route('student.dashboard') }}">
            <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('student.inscription.*')) active @endif" href="{{ route('student.inscription.show') }}">
            <i class="bi bi-file-earmark-text me-1"></i> Mon inscription
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('student.notifications.*')) active @endif" href="{{ route('student.notifications.index') }}">
            <i class="bi bi-bell me-1"></i> Notifications
            @if ($notificationsNonLues > 0)
                <span class="badge text-bg-danger ms-1">{{ $notificationsNonLues }}</span>
            @endif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('student.profil')) active @endif" href="{{ route('student.profil') }}">
            <i class="bi bi-person me-1"></i> Mon profil
        </a>
    </li>
</ul>
