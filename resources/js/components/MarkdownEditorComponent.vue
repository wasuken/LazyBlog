<template>
	<div class="editor">
		<div class="tabs">
			<ul>
				<li v-on:click='() => {if(!isActive1){isActive1=!isActive1;isActive2=false}}'
								v-bind:class='{"is-active":isActive1}'><a>Markdown</a></li>
				<li v-on:click='() => {if(!isActive2){isActive2=!isActive2;isActive1=false}}'
								v-bind:class='{"is-active":isActive2}'><a>HTML Output</a></li>
			</ul>
		</div>
		<div>
			<input type="submit" value="post" class="button is-primary" />
		</div>
		<tagInput></tagInput>
		<div v-if="isActive1">
			<mInput :title="i_title" :body="i_body" @input="i_title = $event[0];i_body = $event[1]"></mInput>
		</div>
		<div v-if="isActive2">
			<mOutput :title="o_title" :body="o_body"
				   :pxBodyWidth="300" :pxBodyHeight="600"></mOutput>
		</div>
	</div>
</template>
<script>
 import marked from 'marked'
 import mInput from './MarkdownInputPairs.vue'
 import mOutput from './MarkdownOutput.vue'
 import tagInput from './TagInputComponent.vue'
 export default {
	 components:{
		 tagInput,
		 mInput,
		 mOutput,
	 },
	 data: function(){
		 return {
			 isActive1: true,
			 isActive2: false,
			 i_body: "",
			 i_title: "",
			 o_body: "",
			 o_title: "",
		 };
	 },
	 methods: {
		 updateIO: function(){
			 this.o_title = marked(this.i_title);
			 this.o_body = marked(this.i_body);
		 },
		 chanageUlIsActive1(){
			 if(!this.isActive1){
				 this.isActive1=!this.isActive1;
				 this.isActive2=false;
			 }
		 },
		 chanageUlIsActive1(){
			 if(!this.isActive1){
				 this.isActive1=!this.isActive1;
				 this.isActive2=false;
			 }
		 },
	 },
	 watch: {
		 i_title: function(v){
			 this.updateIO();
		 },
		 i_body: function(v){
			 this.updateIO();
		 },
	 },
 }
</script>
<style>
 .editor{
	 overflow: scroll;
	 width: 80% !important;
	 vertical-align: top;
	 box-sizing: border-box;
	 padding: 0 20px;
	 margin: 0;
	 height: 100%;
	 font-family: 'Helvetica Neue', Arial, sans-serif;
	 color: #333;
 }
</style>
