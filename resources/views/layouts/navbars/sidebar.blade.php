<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini">{{ __('BD') }}</a>
            <a href="#" class="simple-text logo-normal">{{ __('Dashboard') }}</a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'properti') class="active " @endif>
                <a href="{{ route('pages.properti') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Property') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'rooms') class="active " @endif>
                <a href="{{ route('pages.rooms') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Rooms') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'rates') class="active " @endif>
                <a href="{{ route('pages.rates') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Rate') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'tour') class="active " @endif>
                <a href="{{ route('pages.tour') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Tour') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'destinasi') class="active " @endif>
                <a href="{{ route('pages.destinasi') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Destination') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'aktivitas') class="active " @endif>
                <a href="{{ route('pages.activity') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Activity') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'products') class="active " @endif>
                <a href="{{ route('pages.products') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Products') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'news') class="active " @endif>
                <a href="{{ route('pages.news') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('News') }}</p>
                </a>
            </li>
            <!-- <li>
                <a data-toggle="collapse" href="#laravel-examples" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Laravel Examples') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="laravel-examples">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('User Profile') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('User Management') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li> -->
            <!-- <li @if ($pageSlug == 'icons') class="active " @endif>
                <a href="{{ route('pages.icons') }}">
                    <i class="tim-icons icon-atom"></i>
                    <p>{{ __('Icons') }}</p>
                </a>
            </li> -->
            <!-- <li @if ($pageSlug == 'notifications') class="active " @endif>
                <a href="{{ route('pages.notifications') }}">
                    <i class="tim-icons icon-bell-55"></i>
                    <p>{{ __('Notifications') }}</p>
                </a>
            </li> -->
            <!-- <li @if ($pageSlug == 'tables') class="active " @endif>
                <a href="{{ route('pages.tables') }}">
                    <i class="tim-icons icon-puzzle-10"></i>
                    <p>{{ __('Table List') }}</p>
                </a>
            </li> -->
        </ul>
    </div>
</div>
