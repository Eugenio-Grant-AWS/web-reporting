<div class="fixed overflow-y-auto sidebar d-flex flex-column vh-100 side-bar border-end">
    <a href="" class="toggle-cross d-md-none d-flex z-1"> <i class="fas fa-times"></i></a>
    <div class="p-3 mb-3 logo border-bottom ">
        <a href="/reach-exposure-probability-with-mean"><img src="{{ asset('assets/images/logo.png') }}"
                alt=""></a>
    </div>
    <ul class="gap-3 p-3 mb-0 nav nav-pills flex-column mb-sm-auto align-content-center" id="menu">

        <li class="nav-item">
            <a href="{{ route('reach-exposure-probability-with-mean') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center  {{ request()->is('reach-exposure-probability-with-mean*') ? 'current' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>Reach Exposure / Probability with mean</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('net-percentage-of-consumers-reached') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center  {{ request()->is('net-percentage-of-consumers-reached*') ? 'current' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Net % of Consumers Reached</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('unduplicated-net-reach') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center  {{ request()->is('unduplicated-net-reach*') ? 'current' : '' }}">
                <i class="fas fa-users"></i>
                <span>Unduplicated Net Reach</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('net-reach') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center {{ request()->is('net-reach*') ? 'current' : '' }}">
                <i class="fas fa-bullseye"></i>
                <span>Net Reach</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('advertising-attention-by-touchpoint') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center {{ request()->is('advertising-attention-by-touchpoint*') ? 'current' : '' }}">
                <i class="fas fa-hand-pointer"></i>
                <span>Advertising Attention by Touchpoint</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('reach-attention-plot') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center {{ request()->is('reach-attention-plot*') ? 'current' : '' }}">
                <i class="fas fa-chart-scatter"></i>
                <span>Reach x Attention Plot</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('attentive-exposure') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center {{ request()->is('attentive-exposure*') ? 'current' : '' }}">
                <i class="fas fa-eye"></i>
                <span>Attentive Exposure</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('touchpoint-influence') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center {{ request()->is('touchpoint-influence*') ? 'current' : '' }}">
                <i class="fas fa-star"></i>
                <span>Touchpoint Influence</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('indexed-review-of-stronger-drivers') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center {{ request()->is('indexed-review-of-stronger-drivers*') ? 'current' : '' }}">
                <i class="fas fa-book"></i>
                <span>Indexed Review of Stronger Drivers</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tip-summary') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center {{ request()->is('tip-summary') ? 'current' : '' }}">
                <i class="fas fa-lightbulb"></i>
                <span>TIP Summary</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tip-summary-creative-quality') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center {{ request()->is('tip-summary-creative-quality*') ? 'current' : '' }}">
                <i class="fas fa-pencil-alt"></i>
                <span>TIP Summary x Creative Quality</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('optimized-campaign-summary') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center {{ request()->is('optimized-campaign-summary*') ? 'current' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Optimized Campaign Summary</span>
            </a>
        </li>
        <li class="nav-item">
            @if(Auth::check() && Auth::user()->hasRole('admin'))
            <a href="{{ route('users.index') }}"
                class="gap-2 align-middle nav-link d-flex align-items-center {{ request()->is('users*') ? 'current' : '' }}">
                <i class="fas fa-users"></i>
                <span>User Management</span>
            </a>
            @endif
        </li>
    </ul>

</div>
