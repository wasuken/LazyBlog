<template>
	<div class="box" style="height:600px;overflow: scroll;">
		<input name="title" type="text" class="input" id="i_title"
			   v-model="i_title" placeholder="input title" />
		<textarea name="body" placeholder="input markdown"
				  v-model="i_body"
				  class="is-hovered textarea" rows="50">
			{{this.i_body}}
		</textarea>
	</div>
</template>
<script>
 export default {
	 props:{
		 title: String,
		 body: String,
	 },
	 data: function(){
		 return {
			 i_body: "",
			 i_title: "",
		 };
	 },
	 mounted: function(){
		 this.i_title = this.title;
		 this.i_body = this.body;
	 },
	 watch: {
		 i_title: function(v){
			 this.$emit("input", [this.i_title, this.i_body]);
		 },
		 i_body: function(v){
			 this.$emit("input", [this.i_title, this.i_body]);
		 },
	 },
 }
</script>
