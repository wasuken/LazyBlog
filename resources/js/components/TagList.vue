<template>
<div>
  <div class="content">
	<h4>Tags</h4>
  </div>
  <div class="content">
	<button v-for="(tag,idx) in tagList" :key="idx"
			@click="clickTag(tag.name)">{{tag.name}}</button>
  </div>
</div>
</template>
<script>
import { mapMutations } from 'vuex';
export default{
	data: function(){
		return{
			tagList: [],
		}
	},
    methods: {
		...mapMutations('search', [
			'setResult',
		]),
		getTags: function(){
			fetch('/api/tag')
				.then(resp => resp.json())
				.then(json => this.tagList = json);
		},
		clickTag: function(tagName){
			let query = 'tag=' + tagName;
			fetch(encodeURI('/api/page?' + query))
				.then(resp => resp.json())
				.then(json => this.setResult(json))
		}
	},
	mounted: function(){
		this.getTags();
	}
}
</script>
