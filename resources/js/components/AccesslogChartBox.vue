<template>
	<div class="box">
		<div class="controll">
			<h2>Value</h2>
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

			<h2>Value Range</h2>
			<div>min:<input class="input is-primary" v-model="min" type="number" min="0" /></div>
			<div>max:<input class="input is-primary" v-model="max" type="number"/></div>
			<button class="button is-primary" v-on:click="parseAccessLogsJsonToData()">change</button>
			<hr/>

			<h2>Partical Match Filtering</h2>
			<div>text:<input class="input is-primary" v-model="matchValue" type="text" /></div>
			<button class="button is-primary" v-on:click="parseAccessLogsJsonToData()">change</button>
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
			 min:0,
			 max:10,
			 matchValue: "",
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
			 if(this.matchValue.length > 2){
				 keys = keys.filter(x => x.indexOf(this.matchValue) > -1)
			 }
			 keys.slice(this.min, this.max).forEach(x => cdata.push([x, ds[x]]));
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
