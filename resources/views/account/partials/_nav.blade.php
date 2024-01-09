<ul class="nav nav-pills mb-2">
    <li class="nav-item">
        <a class="nav-link account-nav @if ($active == 'settings'){{ 'active' }}@endif" href="{{ url('account/settings') }}">
            <i data-feather="user" class="font-medium-3 me-50"></i>
            <span class="fw-bold">{{ __('locale.Profile') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link account-nav @if ($active == 'security'){{ 'active' }}@endif" href="{{ url('account/security') }}">
            <i data-feather="lock" class="font-medium-3 me-50"></i>
            <span class="fw-bold">{{ __('locale.Security') }}</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link account-nav @if ($active == 'theme'){{ 'active' }}@endif" href="{{ url('account/theme') }}">
            <i data-feather="layout" class="font-medium-3 me-50"></i>
            <span class="fw-bold">{{ __('locale.Theme') }}</span>
        </a>
    </li>
</ul>