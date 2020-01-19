<template>
	<div>
	  <input type="text" value="" />
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
