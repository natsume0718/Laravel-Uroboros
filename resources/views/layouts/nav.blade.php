<!--Navbar-->
<div>
    <nav class="navbar navbar-expand-md navbar-dark z-depth-1">
        <!-- Navbar brand -->
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png', true) }}" alt="logo">
        </a>
        <!-- Collapse button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicNav"
            aria-controls="basicNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="basicNav">
            <!-- Links -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                </li>
            </ul>
            <div class="md-form my-0">
                <ul class="navbar-nav mr-auto">
                    @guest
                    <li class="nav-item"><a class="nav-link page-scroll" href="{{ route('login') }}">Login</a></li>
                    @else
                    <header id="nav">
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                        @endguest
                </ul>
            </div>
        </div>
        <!-- Collapsible content -->

    </nav>
</div>