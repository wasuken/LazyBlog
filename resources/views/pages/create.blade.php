@extends('layouts.template')

@section('content')
	<div class="box">
		<form action="/page" method="POST">
			@csrf
			<input name="title" class="input" type="text" placeholder="title">
			<textarea class="textarea is-hovered is-rounded" placeholder="body"
					  cols="30" name="body" rows="10"></textarea>
			<tag-input-component></tag-input-component>
			<p><input class="button is-primary" type="submit" value="投稿"/></p>
		</form>
	</div>
@endsection
