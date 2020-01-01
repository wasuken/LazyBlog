@extends('layouts.template')

@section('content')
	<div>
		<input name="token" type="hidden" id="token" value="{{$token}}"/>
		<accesslog-chart-box></accesslog-chart-box>
	</div>
@endsection
