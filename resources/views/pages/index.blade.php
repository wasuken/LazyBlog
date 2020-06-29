@extends('layouts.template')

@section('content')
	<div>
		<!-- 左 -->
		<div>
			<comments></comments>
		</div>
		<!-- 中央 -->
		<div>
			<search-area></search-area>
		</div>
		<!-- 右 -->
		<div>
			<top-ten-most-popular-in-all></top-ten-most-popular-in-all>
			<per-date></per-date>
			<all-tags></all-tags>
		</div>

	</div>
@endsection
