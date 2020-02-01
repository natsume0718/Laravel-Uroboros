@extends('layouts.app')

@section('content')
<div class="container-fluid rgba-grey-slight">
	<div class="row">
		<div class="col-lg-4 col-md-12 col-xs-12"></div>
		<div class="col-lg-4 col-md-12 col-xs-12 text-center my-4">
			<h4 class="h4 text-aqua-gradiention font-weight-lighter fadeIn">活動一覧</h4>
		</div>
		<div class="col-lg-4 col-md-12 col-xs-12"></div>
	</div>
	<div class="row">
		@each('components.activites', $activities, 'activity')
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-12 col-xs-12"></div>
		<div class="col-lg-4 col-md-12 col-xs-12">
			<div class="show-box">
				{!! Form::open(['route' => ['activity.store',$user->nickname]]) !!}
				<div class="md-form">
					<i class="far fa-calendar-check prefix"></i>
					{!! Form::label('inputActivity', '※継続したい活動名：',['class'=>'active']) !!}
					{!! Form::text('name', old('name'), ['class' => 'form-control
					focus-visible','id'=>'inputActivity','data-focus-visible-added'])
					!!}
					@if ($errors->has('name'))
					<div class="text-danger">
						{{ $errors->first('name') }}
					</div>
					@endif
				</div>
				<div class="d-flex justify-content-center">
					{!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
				</div>
				{!! Form::close() !!}
			</div>
		</div>
		<div class="col-lg-4 col-md-12 col-xs-12"></div>
	</div>
</div>
@endsection