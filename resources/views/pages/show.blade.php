@extends('layouts.template')

@section('content')
	<div class="is-centered">
		<div class="card is-centered" style="width:80%;">
			<div class="card-header">
				<div class="card-header-title">
					{{$page->title}}
				</div>
			</div>
			<div class="card-content">
				<div class="tags">
					@foreach($tags as $tag)
						<a class="tag hover-press" href="/tag/{{$tag->name}}">
							{{$tag->name}}
						</a>
					@endforeach
				</div>
				<hr/>
				<div style="word-wrap: break-word;">
					{!! $page->body !!}
				</div>
			</div>
			<div class="card-footer">
				<div class="card-footer-item">
					writer:{{\App\User::find($page->user_id)->name}}
				</div>
			</div>
		</div>
	</div>
@endsection
