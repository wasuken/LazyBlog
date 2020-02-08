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
						@php
						$comment_from_page = \App\Page::find($comment->page_id);
						@endphp
						<div class="content">
							<nav class="level">
								<div class="level-left">
									<a href="/page?id={{$comment_from_page->id}}">
										<small>{{$comment->handle_name}}</small>
									</a>
								</div>
							</nav>
							<hr/>

							<nav class="level">
								{{$comment->comment}}
								<div class="level-right">
									<small>
										{{date('Y年m月d日 h:m:s',  strtotime($comment->created_at))}}
									</small>
								</div>
							</nav>
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
				if(isset($tag)) $params['tag'] = $tag;
				@endphp
				{{$pages->appends($params)->links('vendor.pagination.simple-default')}}
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
							@php
							$page_id = $page->id;
							$pts = array_filter($page_tags, function($v) use ($page_id){
							return ($v->page_id === $page_id);
							});
							@endphp
							@foreach($pts as $page_tag)
								<a href="/?tag={{$page_tag->name}}">{{$page_tag->name}}</a>
							@endforeach
							<hr/>
							@php
							$body_replaced = preg_replace('/<.*?>/i', '', $page->body);
							@endphp
							{{mb_strlen($body_replaced) > 30? mb_substr($body_replaced, 0, 30) . '...' : $body_replaced}}
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
				{{$pages->appends($params)->links('vendor.pagination.simple-default')}}
			</div>
		</div>
		<div style="width:30%;float:left;">
			<div class="box">
				<h3>人気記事</h3>
				<div class="content">
					@foreach($page_mosts_10 as $page)
						<span>
							<a class="is-block" href="/page?id={{$page->id}}">
								{{$page->title}}
							</a>
						</span>
					@endforeach
				</div>
			</div>
		</div>
	</div>
@endsection
