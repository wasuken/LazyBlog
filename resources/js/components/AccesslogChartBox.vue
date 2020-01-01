<template>
	<div class="box">
		<div class="controll">
			<label class="radio" for="ip_address">
				<input name="label_key" type="radio" value="ip_address" id="ip_address" checked
					   v-on:click="changeKey('ip_address')" />
				ip_address
			</label>
			<label class="radio" for="user_agent">
				<input name="label_key" type="radio" value="user_agent" id="user_agent"
					   v-on:click="changeKey('user_agent')"/>
				user_agent
			</label>
			<label class="radio" for="url">
				<input name="label_key" type="radio" value="url" id="url"
					   v-on:click="changeKey('url')"/>url
			</label>
			<hr/>
			<chart
				:chartType="chartType"
								:chartData="chartData"
												:chartOptions="chartOptions"
			/>
		</div>
	</div>
</template>
<script>
 import Chart from './AccesslogGoogleChart';
 export default{
	 components: {
		 Chart,
	 },
	 data: function(){
		 return{
			 labelsKey: "ip_address",
			 chartType: "ColumnChart",
			 chartData: [],
			 json: [],
			 chartOptions: {
				 title: 'アクセスログ集計',
				 subtitle: 'accesslogs',
				 width: '60%',
				 height: 500,
			 }
		 }
	 },
	 methods: {
		 getAccessLogsJson: function(){
			 let token = document.getElementById('token').value;
			 fetch('/api/accesslogs?token=' + token)
				 .then(resp => resp.json())
				 .then(json => {
					 this.json = json;
					 this.parseAccessLogsJsonToData();
				 });
		 },
		 parseAccessLogsJsonToData(){
			 let ds = {};
			 this.json.forEach(x => {
				 if(Number.isInteger(ds[x[this.labelsKey]])){
					 ds[x[this.labelsKey]]++;
				 }else{
					 ds[x[this.labelsKey]] = 1;
				 }
			 })
			 let keys = Object.keys(ds);
			 keys.sort((x, y) => ds[y] - ds[x]);
			 let cdata = [[this.labelsKey, 'value']];
			 keys.slice(0, 10).forEach(x => cdata.push([x, ds[x]]));
			 console.log(cdata);
			 this.chartData = cdata;
		 },
		 changeKey: function(key){
			 this.labelsKey = key;
			 this.parseAccessLogsJsonToData();
		 },
	 },
	 mounted: function(){
		 this.getAccessLogsJson();
	 }
 }

</script>
