<template>
<div class="box">
  <div class="content">
	<h4>Search</h4>
  </div>
  <div class="content">
	<div>
	  <div>Search Word:<input v-model="queries.q" type="text" value="" name="query" placeholder="query" /></div>
	  <div>Tag:<input v-model="queries.tag" type="text" value="" name="tag" placeholder="tag" /></div>
	  <div>Writer:<input v-model="queries.writer" type="text" value="" name="writer" placeholder="writer" /></div>
	</div>
	<div>
	  <div>
		Sort Key:
		<select v-model="queries.sortKey" name="sort-key">
		  <option value="pageView" selected>Page View</option>
		  <option value="date">Date</option>
		</select>
	  </div>
	  <div>
		Order:
		<select v-model="queries.order" name="order">
		  <option value="desc" selected>Desc</option>
		  <option value="asc">Asc</option>
		</select>
	  </div>
	  <div>
		Max Page Count(Max100):<input v-model="queries.count" type="number" value="30" min="1" max="100"/>
	  </div>
	  <hr/>
	  <div>
		<h4>Date Range</h4>
		<div>
		  <p>Begin:<input v-model="queries.pb" type="datetime-local" /></p>
		  <p>End:<input v-model="queries.pe" type="datetime-local" /></p>
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
		setURLParams(){
			let pair=location.search.substring(1).split('&');
			for(let i=0;pair[i];i++) {
				let kv = pair[i].split('=');
				if(this.queries[kv[0]] !== undefined){
					this.queries[kv[0]]=decodeURI(kv[1]);
				}
			}
		},
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
		this.setURLParams();
		this.handleSearch();
	},
}
</script>
