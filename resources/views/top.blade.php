@extends('layouts.app')

@section('header')
<div class="container">
	@include('layouts.message')
	<div class="row space-100">
		<div class="col-lg-7 col-md-12 col-xs-12">
			<div class="contents">
				<h2 class="head-title">継続を記録しよう<br> With Uroboros App</h2>
				<div class="header-button">
					@guest
					<a href="{{ route('login') }}" class="btn btn-tw">
						<i class="fab fa-twitter pr-1"></i> Twitterで登録・ログイン
					</a>
					@else
					<p>
						<a class="btn btn-outline-info waves-effect"
							href="{{ route('activity.index',$user ? $user->nickname :null) }}">
							<i class="fas fa-pen fa-lg" style="margin-right:0.8em"></i>マイページへ</a>
					</p>
					@endguest
				</div>
			</div>
		</div>
		<div class="col-lg-5 col-md-12 col-xs-12">
			<div class="intro-img">
				<img src="{{ asset('img/intro-mobile.png', true) }}" alt="logo">
			</div>
		</div>
	</div>
</div>
@endsection