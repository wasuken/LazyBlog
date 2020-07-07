<template>
<div>
  <div class="box">
	Page:{{lastQueryParams.current}}/{{allCount}}
	<hr/>
	<!-- ここにページング用の要素周りの処理を入れる。 -->
	<nav aria-label="Page navigation">
	  <ul class="pagination">
		<button class="page-item" @click="clickListMove(-1)">Prev</button>
		<button class="page-item" @click="clickListMove(1)">Next</button>
	  </ul>
	</nav>
	<hr/>
  </div>
  <!-- ページのリストを回す -->
  <div class="box" v-for="(page,idx) in pageList" :key="idx">
	<div class="content">
	  <p>
		<a :href="'/page?id=' + page.id"><h3>{{page.title}}</h3></a>
		<hr/>
		<!-- ページのタグをループ -->
		<button v-for="(tag,i) in page.tags" :key="i"
				@click="clickTag(tag)">
		  {{tag}}
		</button>
		<hr/>
		<!-- bodyを表示。なお、30文字以下に切り捨てる -->
		{{(page.body || '').length <= 30 ? page.body : page.body.slice(0, 30)}}
									  </p>
	</div>

	<hr/>
	<nav class="level">
	  <div class="level-left">
		writer: <a class="level-item"
				   :href="'/page?id=' + page.user_name">
		  {{page.user_name}}
		</a>
	  </div>
	</nav>
  </div>

  <div class="box">
	Page:{{lastQueryParams.current}}/{{allCount}}
	<hr/>
	<!-- ここにページング用の要素周りの処理を入れる。 -->
	<nav aria-label="Page navigation">
	  <ul class="pagination">
		<button class="page-item" @click="clickListMove(-1)">Prev</button>
		<button class="page-item" @click="clickListMove(1)">Next</button>
	  </ul>
	</nav>
	<hr/>
  </div>
</div>
</template>
<script>
import { mapState, mapGetters, mapMutations } from 'vuex';
export default {
	computed: {
		...mapState('search', {
			pageList: 'searchResult',
			allCount: 'lastSearchResultAllPageCount',
			lastQueryParams: 'lastQueryParams',
		}),
	},
	methods: {
		...mapMutations('search', [
			'setResult',
			'setLastQueryParams',
		]),
		...mapGetters('search', [
			'getLastQueryParams',
		]),
		clickTag: function(tagName){
			let query = 'tag=' + tagName;
			fetch(encodeURI('/api/page?' + query))
				.then(resp => resp.json())
				.then(json => this.setResult(json));
		},
		clickListMove: function(value){
			let queries = this.getLastQueryParams();
			let changedCurrent = queries.current + value;

			if(changedCurrent <= this.allCount && 0 < changedCurrent){
				console.log(changedCurrent);
				console.log(this.allCount);
				queries.current = changedCurrent;
				fetch(encodeURI('/api/page?' + (Object.keys(queries)
												.filter(x => queries[x] !== "")
												.map(x => x + '=' + queries[x])
												.join('&'))))
					.then(resp => resp.json())
					.then(json => this.setResult(json))
					.then(x => this.setLastQueryParams(queries));
			}
		}
	},
}
</script>
