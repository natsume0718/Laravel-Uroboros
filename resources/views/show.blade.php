@extends('layouts.app')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.css">
@section('content')
<div class="container-fluid rgba-grey-slight">
	@include('components.message')
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
					<li class="list-group-item">継続日数：{{ $activity->continuation_days }} 日</li>
				</ul>
				{!! Form::label('disp', '前回の投稿をフォームに表示：') !!}
				{!! Form::checkbox('disp', old('disp'),false, ['id'=>'js-check']) !!}
				{!! Form::open(['method' => 'POST','route' =>
				['post.store',$user->nickname,$activity->name]]) !!}
				<div class="form-group">
					{!! Form::label('hour', '活動時間：') !!}
					{!! Form::text('hour',old('hour'), ['class' =>'form-control
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
					<span id="js-showCountText">0</span>/140
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
		<div class="col-lg-4 col-md-12 col-xs-12">
			<canvas id="Chart" style="max-width: 500px;"></canvas>
		</div>
	</div>
	<canvas id="myChart" width="400" height="400"></canvas>
	<div class="row justify-content-center">
		<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive" style="height:40vh; overflow: scroll; margin-top:1em;">
				<table class="table table-stiped table-bordered" style="background-color:white;">
					<tr>
						<th>ユーザー</th>
						<th>活動内容</th>
						<th>投稿時刻</th>
						<th>活動時間</th>
						<th></th>
						<th></th>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script src="{{ mix('js/activity/app.js') }}" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script>
	var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>

@endpush