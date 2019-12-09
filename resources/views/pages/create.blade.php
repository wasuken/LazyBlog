@extends('layouts.template')

@section('content')
	<div class="box">
		<form action="/page" method="POST">
			@csrf
			<input name="title" class="input" type="text" placeholder="title">
			<textarea class="textarea is-hovered is-rounded" placeholder="body"
					  cols="30" name="body" rows="10"></textarea>
			tags:<div class="select is-multiple">
				<select name="tags[]" multiple>
					@foreach(\App\Tag::all() as $tag)
						<option value="{{$tag->name}}">{{$tag->name}}</option>
					@endforeach
				</select>
			</div>
			<p><input class="button is-primary" type="submit" value="投稿"/></p>
		</form>
	</div>
@endsection
