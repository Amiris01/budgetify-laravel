<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/favicon.ico') }}" alt="Logo" width="30"
                class="d-inline-block align-text-top">
            Budgetify
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                @guest
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                @endguest

                @auth
                    <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="nav-link {{ Route::is('apparels.index') ? 'active' : '' }}"
                        href="{{ route('apparels.index') }}">Apparels</a>

                    <a class="nav-link {{ Route::is('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">Events</a>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ Route::is('wallets.index') || Route::is('budgets.index') || Route::is('transactions.index') ? 'active' : '' }}"
                            href="#" id="financeDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Finance
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="financeDropdown">
                            <li><a class="dropdown-item {{ Route::is('wallets.index') ? 'active' : '' }}"
                                    href="{{ route('wallets.index') }}">Wallets</a></li>
                            <li><a class="dropdown-item {{ Route::is('budgets.index') ? 'active' : '' }}"
                                    href="{{ route('budgets.index') }}">Budgets</a></li>
                            <li><a class="dropdown-item {{ Route::is('transactions.index') ? 'active' : '' }}"
                                    href="{{ route('transactions.index') }}">Transactions</a></li>
                        </ul>
                    </div>
                @endauth
            </div>

            <div class="navbar-nav ms-auto">
                @auth
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link">Logout</button>
                    </form>
                    @else
                        <a class="nav-link {{ Route::is('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                        <a class="nav-link {{ Route::is('register') ? 'active' : '' }}"
                            href="{{ route('register') }}">Register</a>
                    @endauth
            </div>
        </div>
    </div>
</nav>
