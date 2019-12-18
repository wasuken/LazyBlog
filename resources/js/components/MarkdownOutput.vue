<template>
	<div class="box" style="height:600px;overflow: scroll;">
		<div id="title" class="o-title" v-html="title"></div>
		<hr/>
		<div id="body" class="o-body" v-html="body"></div>
	</div>
</template>
<script>
 export default {
	 props:{
		 title: String,
		 body: String,
		 pxBodyWidth: Number,
		 pxBodyHeight: Number,
	 },
	 mounted: function(){
		 document.getElementById("body").style.width = this.pxBodyWidth + "px";
		 document.getElementById("body").style.height = this.pxBodyHeight + "px";
	 },
 }
</script>
