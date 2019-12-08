@extends('layouts.template')

@section('content')
	<div>
		{{ $pages->links('vendor.pagination.default') }}
	</div>
	<div class="columns is-multiline">
		@foreach($pages as $page)
			<div class="card is-one-fifth column" style="flex: 0 0 30%;">
				<div class="card-header">
					<a class="hover-press" href="/page?id={{$page->id}}">
						<div class="card-header-title">
							{{$page->title}}
						</div>
					</a>
				</div>
				@php
				$page_user = \App\User::find($page->user_id);
				@endphp
				<div class="card-content">
					{{mb_strlen($page->body) > 30? mb_substr($page->body, 0, 30) . '...' : $page->body}}
				</div>
				<div class="card-footer">
					<div class="card-footer-item">
						writer:
						<a class="hover-press" href="/pages?writer={{$page_user->name}}">
							{{$page_user->name}}
						</a>
					</div>

				</div>
			</div>

		@endforeach
	</div>
	<div>
		@php
		$params = [];
		if(isset($writer)) $params['writer'] = $writer;
		// if(isset($q)) $params['q'] = $q;
		@endphp
		{{$pages->appends($params)->links('vendor.pagination.default')}}
	</div>


@endsection
