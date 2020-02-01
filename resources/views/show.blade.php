@extends('layouts.app')

@section('content')
<div class="container-fluid rgba-grey-slight">
	<div class="row">
		<div class="col-lg-4 col-md-12 col-xs-12"></div>
		<div class="col-lg-4 col-md-12 col-xs-12 text-center my-4">
			<h4 class="h4 text-aqua-gradiention font-weight-lighter fadeIn">{{$activity->name}}</h4>
		</div>
		<div class="col-lg-4 col-md-12 col-xs-12"></div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-12 col-xs-12"></div>
		<div class="col-lg-4 col-md-12 col-xs-12">
			<div class="show-box">
				<a href="{{ route('activity.index',$user->nickname) }}">マイページへ</a>
				<ul class="list-group">
					<li class="list-group-item">合計時間：{{ $total->total_hour ?? 0 }}</li>
					<li class="list-group-item">活動日数：{{ $active_day }} 日</li>
					<li class="list-group-item">継続日数：{{ $continuation_days }} 日</li>
				</ul>
				{!! Form::open(['method' => 'POST','route' =>
				['post.store',$user->nickname,$activity->name]]) !!}
				<div class="form-group">
					{!! Form::label('hour', '活動時間：') !!}
					{!! Form::text('hour',old('hour','00:00'), ['class' =>'form-control
					js-flatpickr-time bg-white','id'=>'js-selectbox','min'=>'0']) !!}
					@if ($errors->has('hour'))
					<span class="text-danger">
						{{ $errors->first('hour') }}
					</span>
					@endif
				</div>
				<div class="form-group">
					{!! Form::label('tweet', '※活動内容をTwitterに投稿：') !!}
					{!! Form::textarea('tweet', old('name'), ['class' =>
					'form-control','id'=>'js-countText','rows'=>5]) !!}
					<div class="d-flex w-100 justify-content-between">
						<span class="btn btn-sm btn-info" id="js-fetchPrevContent"
							data-url="{{route('post.latest',[$user->nickname,$activity->name])}}">前回の投稿をフォームに表示</span>
						<div><span id="js-showCountText">0</span>/140</div>
					</div>
					@if ($errors->has('tweet'))
					<span class="text-danger">
						{{ $errors->first('tweet') }}
					</span>
					@endif
				</div>
				<div class="form-group">
					{!! Form::label('is_reply', 'リプライ形式で投稿：') !!}
					{!! Form::checkbox('is_reply', 1, ['class' => 'form-control']) !!}
					@if ($errors->has('is_reply'))
					<span class="text-danger">
						{{ $errors->first('is_reply') }}
					</span>
					@endif
				</div>
				<div class="d-flex justify-content-center">
					{!! Form::submit('保存', ['class' => 'btn btn-primary','id'=>'js-submitContent']) !!}
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	@if($posts && $posts->isNotEmpty())
	@include('components.postsTable',compact('posts','activity'))
	@endempty
</div>
@endsection
@push('scripts')
<script src="{{ mix('js/activity/app.js') }}" defer></script>
@endpush