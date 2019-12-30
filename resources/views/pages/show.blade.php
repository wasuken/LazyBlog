@extends('layouts.template')

@section('content')
	<div class="is-centered" style="width:80%;">
		<div class="card is-centered">
			<div class="card-header">
				<div class="card-header-title">
					<div style="width:100%;">{{$page->title}}</div>
					@php
					use Illuminate\Support\Facades\Auth;
					@endphp
					@if(\App\User::find($page->user_id)->id === (empty(Auth::user())? null : Auth::user()->id))
						<div class="is-pulled-right">
							<form action="" method="POST">
								@csrf
								{{ method_field('delete') }}
								<input name="id" type="hidden" value="{{$page->id}}"/>
								<button class="button is-primary">削除</button>
							</form>
						</div>
					@endif
				</div>
			</div>
			<div class="card-content">
				<div class="tags">
					@foreach($tags as $tag)
						<a class="tag hover-press" href="/pages?tag={{$tag->name}}">
							{{$tag->name}}
						</a>
					@endforeach
				</div>
				<hr/>
				<div style="word-wrap: break-word;" class="markdown-body">
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
			<h3>Send Comment</h3>
			<hr/>
			※諸事情によりユーザ名とコメントにurlは貼れないようにしております。
			<form action="/comment" method="POST">
				@csrf
				<input name="id" type="hidden" value="{{$page->id}}"/>
				<input class="input" name="user" type="text" value="" placeholder="user name">
				<textarea name="comment" class="textarea" placeholder="comment"></textarea>
				<input class="button" type="submit" value="Send"/>
			</form>
		</div>
		<div class="box">
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
