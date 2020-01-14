<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Styles -->
	<link rel="stylesheet" href="{{ mix('/css/app.css') }}" defer>

</head>

<body>
	<header id="nav">
		{{-- <div class="overlay"></div>
		<nav class="navbar navbar-expand-md bg-inverse fixed-top scrolling-navbar menu-bg">
			<div class="container">
				<a href="{{ url('/') }}" class="navbar-brand"><img src="{{ asset('img/logo.png', true) }}" alt="logo"></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
			aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<i class="lni-menu"></i>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav mr-auto w-100 justify-content-end">
				@guest
				<li class="nav-item"><a class="nav-link page-scroll" href="{{ route('login') }}">Login</a></li>
				@else
				<li class="nav-item">
					<a class="nav-link page-scroll" href="{{ route('logout') }}"
						onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
					</form>
				</li>
				@endguest
			</ul>
		</div>
		</div>
		</nav> --}}
		@include('layouts.nav')
		<!-- Header Section End -->
		@yield('header')
	</header>
	@yield('content')
	<!-- Footer Section Start -->
	<footer>
		@include('layouts.footer')
	</footer>
	<!-- Scripts -->
	<script src="{{ mix('js/app.js') }}" defer></script>
	@stack('scripts')
</body>

</html>