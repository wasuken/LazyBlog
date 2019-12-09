@extends('layouts.template')

@section('content')
	<div class="is-centered" style="width:80%;">
		<div class="card is-centered">
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
					@php
					$page_user = \App\User::find($page->user_id);
					@endphp
					writer:<a href="/pages?writer={{$page_user->name}}">{{$page_user->name}}</a>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="content">
				<a href="https://twitter.com/share?ref_src=twsrc%5Etfw&text=[{{config('app.name', '')}}]{{$page->title}}"
				   class="twitter-share-button" data-show-count="false">Tweet</a>
				<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
			</div>
			<h3>Comments</h3>
			<hr/>
			@foreach($comments as $comment)
				<div class="box">
					<div class="content">
						<nav class="level">
							<div class="level-left">
								<small>{{$comment->handle_name}}</small>
							</div>
						</nav>
						<hr/>
						{{$comment->comment}}
					</div>
				</div>
			@endforeach
		</div>
	</div>
@endsection
