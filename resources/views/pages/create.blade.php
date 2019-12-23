@extends('layouts.template')

@section('content')
	<div style="height:100%;">
		<form action="/page" method="POST">
			@csrf
			<input name="type" type="hidden" value="md"/>
			<markdown-editor-component></markdown-editor-component>
		</form>
	</div>
@endsection
