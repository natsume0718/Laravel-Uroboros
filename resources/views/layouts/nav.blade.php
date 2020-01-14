<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark primary-color">

    <!-- Navbar brand -->
    <a href="{{ url('/') }}" class="navbar-brand"><img src="{{ asset('img/logo.png', true) }}" alt="logo"></a>
    <!-- Collapse button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicNav" aria-controls="basicNav"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible content -->
    <div class="collapse navbar-collapse" id="basicNav">

        <!-- Links -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Home
                    <span class="sr-only">(current)</span>
                </a>
            </li>


        </ul>
        <!-- Links -->

        <form class="form-inline" href="{{ route('logout') }}" method="POST">
            {{ csrf_field() }}
            <div class="md-form my-0">
                <ul class="navbar-nav mr-auto">
                    @guest
                    <li class="nav-item"><a class="nav-link page-scroll" href="{{ route('login') }}">Login</a></li>
                    @else
                    <li class="nav-item">
                        <button type="submit">ログアウト</button>
                    </li>
                    @endguest
                </ul>
            </div>
        </form>
    </div>
    <!-- Collapsible content -->

</nav>
<!--/.Navbar-->