<template>
	<div>
		<div class="columns">
			<div class="column">
				<input id="tags_string" name="tags_string" type="text"
					   class="input is-half"
					   placeholder="input tags(comma space)" v-model="tagsString" />
			</div>
			<div class="column">
				<input type="button" value="clear" v-on:click="clearTags" class="button is-primary" />
			</div>
		</div>
		<div class="tags">
			<div v-for="tag in tags" >
				<a href="javascript:void(0)" class="tag hover-press">
					{{tag}}
				</a>
				<input name="tags[]" type="hidden" :value="tag" />
			</div>
		</div>
	</div>
</template>

<script>
 export default {
	 data: function(){
		 return {
			 tagsString: "",
			 tags: [],
		 };
	 },
	 methods: {
		 clearTags: function(){
			 this.tagsString = "";
		 }
	 },
	 watch: {
		 tagsString: function(v){
			 this.tags = Array.from(new Set(v.split(' ')));
			 this.tags = this.tags.filter(x => x);
		 }
	 }
 }
</script>
<style>
 .container{
	 height: 60%;
 }
</style>
