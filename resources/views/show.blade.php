@extends('layouts.app')

@section('content')
<section>
	@include('layouts.message')
	<div id="app-features" class="section">
		<div class="container">
			<div class="section-header">
				<h2 class="section-title wow fadeIn animated" data-wow-delay="0.2s"
					style="visibility: visible;-webkit-animation-delay: 0.2s; -moz-animation-delay: 0.2s; animation-delay: 0.2s;">
					{{ $activity->name }}</h2>
			</div>
			<div class="row">
				<div class="col-lg-4 col-md-12 col-xs-12"></div>
				<div class="col-lg-4 col-md-12 col-xs-12">
					<div class="show-box">
						<a href="{{ route('activity.index',$user->nickname) }}">マイページへ</a>
						<ul class="list-group">
							<li class="list-group-item">合計時間：{{ $total->total_hour ?? 0 }} 時間</li>
							<li class="list-group-item">活動日数：{{ $activity->activity_days }} 日目</li>
							<li class="list-group-item">継続日数：{{ $activity->continuation_days }} 日</li>
						</ul>
						{!! Form::label('disp', '前回の投稿をフォームに表示：') !!}
						{!! Form::checkbox('disp', old('disp'),false, ['id'=>'js-check']) !!}
						{!! Form::open(['method' => 'PATCH','route' =>
						['activity.update',$user->nickname,$activity->name]]) !!}
						<div class="form-group">
							{!! Form::label('hour', '活動時間：') !!}
							{!! Form::time('hour',old('hour'), ['class' =>
							'form-control','id'=>'js-selectbox','min'=>'0']) !!}
							@if ($errors->has('hour'))
							<span style="color:red;">
								{{ $errors->first('hour') }}
							</span>
							@endif
						</div>
						<div class="form-group">
							{!! Form::label('tweet', '※活動内容をTwitterに投稿：') !!}
							{!! Form::textarea('tweet', old('name'), ['class' =>
							'form-control','id'=>'js-countText','rows'=>5]) !!}
							<div id="js-error-color"><span class="js-show-countText">0</span>/140</div>
							@if ($errors->has('tweet'))
							<span style="color:red;">
								{{ $errors->first('tweet') }}
							</span>
							@endif
						</div>
						<div class="form-group">
							{!! Form::label('is_reply', 'リプライ形式で投稿：') !!}
							{!! Form::checkbox('is_reply', 1, ['class' => 'form-control']) !!}
							@if ($errors->has('is_reply'))
							<span style="color:red;">
								{{ $errors->first('is_reply') }}
							</span>
							@endif
						</div>
						<div style="display:flex;justify-content: center;">
							{!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
						</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive" style="height:40vh; overflow: scroll; margin-top:1em;">
				@isset($tweets)
				<table class="table table-stiped table-bordered" style="background-color:white;">
					<tr>
						<th>ユーザー</th>
						<th>活動内容</th>
						<th>投稿時刻</th>
						<th>活動時間</th>
						<th></th>
						<th></th>
					</tr>
					@foreach ($tweets as $tweet)
					<tr>
						<td><img src="{{ $user->twitter_avatar }}" alt=""><span>{{ $user->twitter_name }}</span>
						</td>
						<td>{!! nl2br(e($tweet->body)) !!}</td>
						<td>{{ $tweet->created_at }}</td>
						<td>{{ $tweet->hour }} 時間</td>
						<td>
							<a target="_blank"
								href="https://twitter.com/{{$user->twitter_nickname}}/status/{{$tweet->tweet_id}}">Twitterで表示</a>
						</td>
						<td>
							@if($loop->first)
							{!! Form::open(['method' => 'DELETE','route'
							=>['tweet.delete',$user->twitter_nickname,$activity->task_id,$tweet->tweet_id],
							'class'=>'d-inline'])!!}
							{!! Form::submit('削除', ['class'=>'btn btn-danger']) !!}
							{!! Form::close() !!}
							@endif
						</td>
					</tr>
					@endforeach
				</table>
				@endisset
			</div>
		</div>
	</div>
</section>
@endsection