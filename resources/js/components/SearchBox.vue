<template>
<div class="box">
  <div class="content">
	<h4>Search</h4>
  </div>
  <div class="content">
	<div>
	  <div>検索ワード:<input v-model="queries.q" type="text" value="" name="query" placeholder="query" /></div>
	  <div>タグ:<input v-model="queries.tag" type="text" value="" name="tag" placeholder="tag" /></div>
	  <div>記事作成者:<input v-model="queries.writer" type="text" value="" name="writer" placeholder="writer" /></div>
	</div>
	<div>
	  <div>
		並び替え:
		<select v-model="queries.sortKey" name="sort-key">
		  <option value="pageView" selected>閲覧数</option>
		  <option value="date">日付</option>
		</select>
	  </div>
	  <div>
		並び替え順:
		<select v-model="queries.order" name="order">
		  <option value="desc" selected>降順</option>
		  <option value="asc">昇順</option>
		</select>
	  </div>
	  <div>
		取得記事数(最大100):<input v-model="queries.count" type="number" value="30" min="1" max="100"/>
	  </div>
	  <hr/>
	  <div>
		<h4>作成日絞り込み</h4>
		<div>
		  <p>開始:<input v-model="queries.pb" type="datetime-local" /></p>
		  <p>終了:<input v-model="queries.pe" type="datetime-local" /></p>
		</div>
	  </div>
	  <p>
		<button @click="handleSearch">検索</button>
	  </p>
	</div>
  </div>
</div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
export default {
	data: function(){
		return {
			queries: {
				q: "",
				tag: "",
				writer: "",
				sortKey: "",
				order: "",
				count: 30,
				current: 1,
				pb: "",
				pe: "",
			}
		};
	},
	methods: {
		...mapMutations('search', [
			'setResult',
			'setLastQueryParams',
		]),
		...mapGetters('search', [
			'getLastQueryParams'
		]),
		handleSearch(){
			fetch(encodeURI('/api/page?' + (Object.keys(this.queries)
											.filter(x => this.queries[x] !== "")
											.map(x => x + '=' + this.queries[x])
											.join('&'))))
				.then(resp => resp.json())
				.then(json => this.setResult(json))
				.then(x => this.setLastQueryParams(this.queries));
		}
	},
	mounted: function(){
		this.handleSearch();
		this.queries = this.getLastQueryParams();
	},
}
</script>
