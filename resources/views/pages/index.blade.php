@extends('layouts.template')

@section('content')
	<div>
		<div class="box" style="width:30%;float:left;">
			<div class="content">
				<h4>Comments</h4>
			</div>
			<div class="content">
				@foreach($all_comments as $comment)
					<div class="box">
						<div class="content">
							<nav class="level">
								<div class="level-left">
									@php
									$comment_from_page = \App\Page::find($comment->page_id);
									@endphp
									<a href="/page?id={{$comment_from_page->id}}">
										<small>{{$comment->handle_name}}</small>
									</a>
								</div>
							</nav>
							<hr/>
							{{$comment->comment}}
						</div>
					</div>
				@endforeach
			</div>
		</div>
		<div style="width:40%;float:left;">
			<div>
				@php
				$params = [];
				if(isset($writer)) $params['writer'] = $writer;
				// if(isset($q)) $params['q'] = $q;
				@endphp
				{{$pages->appends($params)->links('vendor.pagination.default')}}
			</div>
			@foreach($pages as $page)
				<div class="box">
					<div class="content">
						@php
						$page_user = \App\User::find($page->user_id);
						@endphp
						<p>
							<a href="/page?id={{$page->id}}"><h3>{{$page->title}}</h3></a>
							<hr/>
							{{mb_strlen($page->body) > 30? mb_substr($page->body, 0, 30) . '...' : $page->body}}
						</p>
					</div>
					<hr/>
					<nav class="level">
						<div class="level-left">
							writer:<a class="level-item" href="/pages?writer={{$page_user->name}}">{{$page_user->name}}</a>
						</div>
					</nav>
				</div>

			@endforeach
			<div>
				{{$pages->appends($params)->links('vendor.pagination.default')}}
			</div>
		</div>
		<div style="width:40%;float:left;">
			<div class="box">
				<h3>人気記事</h3>
				<div class="content">

				</div>
			</div>
		</div>
	</div>
@endsection
