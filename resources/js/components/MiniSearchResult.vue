<template>
  <div>
	<div class="content">
	  <h4>{{title}}</h4>
	</div>
	<p v-for="(page, i) in pageList" :key="i">
	  <a :href="'/page?id=' + page.id">{{page.title}}</a>
	</p>
  </div>
</template>
<script>

export default{
	data: function(){
		return{
			pageList: [],
		}
	},
	mounted: function(){
		fetch(encodeURI('/api/page?' + this.query))
			.then(resp => resp.json())
			.then(json => this.pageList = json.data);
	},
	props: {
		query: '',
		title: '',
	}
}
</script>
