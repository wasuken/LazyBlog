<template>
<div>
  <div class="content">
	<h4>Comments</h4>
  </div>
  <div class="content">
	<!-- 以下、コメント一つごとに作成していく -->
	<div class="box" v-for="(comment, idx) in commentList" :key="idx">
	  <div class="content">
		<nav class="level">
		  <div class="level-left">
			<a :href="'/page?id=' + comment['page_id']">
			  <!-- ハンドルネーム -->
			  <small>{{comment['handle_name']}}</small>
			</a>
		  </div>
		</nav>

		<hr/>

		<nav class="level">
		  <!-- コメントコンテンツ -->
		  {{comment['comment']}}
		  <div class="level-right">
			<small>
			  <!-- 日付 -->
			  {{comment['updated_at']}}
			</small>
		  </div>
		</nav>
	  </div>
	</div>
  </div>
</div>
</template>
<script>
export default{
	data: function(){
		return{
			commentList: [],
		}
	},
	methods: {
		getComments(){
			fetch('/api/comment')
				.then(resp => resp.json())
				.then(json => this.commentList = json.slice(0, 10));
		}
	},
	mounted: function(){
		this.getComments();
	}
}
</script>
