<template>
	<div>
		<div>
			<input name="label_key" type="radio" value="ip_address" id="ip_address" checked
				   v-on:click="changeKey('ip_address')" />
			<label for="ip_address">ip_address</label>
		</div>
		<div>
			<input name="label_key" type="radio" value="user_agent" id="user_agent"
				   v-on:click="changeKey('user_agent')"/>
			<label for="user_agent">user_agent</label>
		</div>
		<div>
			<input name="label_key" type="radio" value="url" id="url"
				   v-on:click="changeKey('url')"/>
			<label for="url">url</label>
		</div>
		<hr/>
		<chart :labelsKey="labelsKey" ref="chr"></chart>
	</div>
</template>
<script>
 import chart from './AccesslogChart';
 export default{
	 components: {
		 chart,
	 },
	 data: function(){
		 return{
			 labelsKey: "ip_address",
		 }
	 },
	 methods: {
		 changeKey: function(key){
			 this.labelsKey = key;
			 this.$refs.chr.updateKey(key);
		 },
	 },
 }

</script>
