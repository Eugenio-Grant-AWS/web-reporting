<div class="fixed overflow-y-auto sidebar d-flex flex-column vh-100 side-bar border-end">
    <a href="" class="toggle-cross d-md-none d-flex"> <i class="fas fa-times"></i></a>
    <div class="p-3 mb-3 logo border-bottom ">
        <a href="#"><img src="{{ asset('assets/images/Logo.png') }}" alt=""></a>
    </div>
    <ul class="gap-3 p-3 mb-0 nav nav-pills flex-column mb-sm-auto align-items-sm-start align-content-center"
        id="menu">

        <li class="nav-item">
            <a href="{{ route('bar-chart') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center  {{ request()->is('bar-chart*') ? 'current' : '' }}">
                <i class="fas fa-th-large"></i>
                <span class="ms-1 ">Reach Exposure/Probability with mean</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pie-chart') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center  {{ request()->is('pie-chart*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">Net % of Consumers Reached</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('line-chart') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center  {{ request()->is('line-chart*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">Unduplicated Net Reach</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('venn-diagram') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center {{ request()->is('venn-diagram*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">Net Reach</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('attentive-exposure') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center {{ request()->is('attentive-exposure*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">Advertising Attention by Touchpoint</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('scatter-plot') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center {{ request()->is('scatter-plot*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">Reach x Attention Plot</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('heat-map') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center {{ request()->is('heat-map*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">Attentive Exposure</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('touchpoint-influence') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center {{ request()->is('touchpoint-influence*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">Touchpoint Influence</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('indexed-chart') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center {{ request()->is('indexed-chart*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">Indexed Review of Stronger Drivers</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tip-summary') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center {{ request()->is('tip-summary*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">TIP Summary</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('summary-chart') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center {{ request()->is('summary-chart*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">TIP Summary x Creative Quality</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('optimized-campaign-summary') }}"
                class="gap-3 align-middle nav-link d-flex align-items-center {{ request()->is('optimized-campaign-summary*') ? 'current' : '' }}">
                <i class="fas fa-exclamation-circle"></i>
                <span class="ms-1 ">Optimized Campaign Summary</span>
            </a>
        </li>
    </ul>

</div>
