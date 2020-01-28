@extends('layouts.app')

@section('content')
<div class="container-fluid bg-aqua-gradiention">
	@include('components.message')
	<div class="row pt-5">
		<div class="col-lg-5 col-md-12 col-xs-12">
			<h1 class="h1 text-white font-weight-bold">継続を記録しよう<br> With
				Uroboros App
			</h1>
			<div class="mt-3">
				@guest
				<a href="{{ route('login') }}">
					<button type="button" class="btn btn-white rounded-pill text-info">
						<i class="fab fa-twitter pr-1 fa-lg"></i>
						Twitterで登録・ログイン</button>
				</a>
				@else
				<p>
					<a href="{{ route('activity.index',$user ? $user->nickname :null) }}">
						<button type="button" class="btn btn-white rounded-pill text-info">
							<i class="fas fa-pen fa-lg"></i>
							マイページへ</button>
					</a>

				</p>
				@endguest
			</div>
		</div>
		<div class="col-lg-2"></div>
		<div class="col-lg-5 col-md-12 col-xs-12 mt-2">
			<div class="intro-img">
				<img src="{{ asset('img/intro-mobile.png', true) }}" class="img-fluid" alt="logo">
			</div>
		</div>
	</div>
</div>
@endsection