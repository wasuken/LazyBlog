@php
use \App\User;
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Scripts -->
		<script src="{{ asset('js/app.js') }}" defer></script>

		<!-- Fonts -->
		<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

		<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	</head>
	<body>
		<div id="app">
			<nav class="navbar is-info">
				<div class="navbar-brand">
					<a class="navbar-item" href="/">{{config('app.name', 'Laravel')}}</a>
				</div>
				<!-- <div class="navbar-menu">
					 <a class="navbar-item" href="/links">links</a>
					 </div> -->
				<div class="navbar-end">
					<div class="buttons">
						@guest
						<div class="navbar-item">
							<a class="button is-primary" href="/login">login</a>
						</div>
							@else
						<div class="navbar-item">
							<a class="button is-primary" href="/page/create">post</a>
						</div>
						<div class="navbar-item">
							<a class="button is-primary" href="javascript:void(0)">{{Auth::user()->name}}</a>
						</div>
						<div class="navbar-item">
							<form action="/logout" method="POST">
								@csrf
								<input class="button is-primary" type="submit" value="logout"/>
							</form>
						</div>
						@endguest
					</div>
				</div>
			</nav>
			<main>
				@if ($errors->any())
					<article class="message">
						<div class="message-header">
							errors
						</div>
						<div class="message-body">
							@foreach ($errors->all() as $error)
								<div class="has-text-danger">{{ $error }}</div>
							@endforeach
						</div>
					</article>
				@endif
				@yield('content')
			</main>
		</div>

	</body>
</html>
