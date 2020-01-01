<script>
 import { Bar } from 'vue-chartjs';
 export default {
	 extends: Bar,
	 name: 'chart',
	 props: {
		 labelsKey: String,
	 },
	 data: function() {
		 return{
			 lKey: "",
			 options: {
				 scales: {
					 xAxes: [{
						 scaleLabel: {
							 display: true,
							 labelString: 'access count'
						 }
					 }],
					 yAxes: [{
						 ticks: {
							 beginAtZero: true,
							 stepSize: 10,
						 }
					 }]
				 }
			 }
		 }
	 },
	 methods: {
		 getAccessLogsJson: function(){
			 let token = document.getElementById('token').value;
			 fetch('/api/accesslogs?token=' + token)
				 .then(resp => resp.json())
				 .then(json => {
					 this.parseAccessLogsJsonToData(json);
				 });
		 },
		 parseAccessLogsJsonToData: function(json){
			 let ds = {};
			 json.forEach(x => {
				 if(Number.isInteger(ds[x[this.labelsKey]])){
					 ds[x[this.lKey]]++;
				 }else{
					 ds[x[this.lKey]] = 1;
				 }
			 })
			 let keys = Object.keys(ds);
			 keys.sort((x, y) => ds[y] - ds[x]);
			 let dset = [
				 {
					 label: this.lKey + "top 10",
					 data: keys.map(x => ds[x]).slice(0, 10),
					 backgroundColor: [
						 'rgba(255, 99, 132, 0.2)',
						 'rgba(54, 162, 235, 0.2)',
						 'rgba(255, 206, 86, 0.2)',
						 'rgba(75, 192, 192, 0.2)',
						 'rgba(153, 102, 255, 0.2)',
						 'rgba(255, 159, 64, 0.2)'
					 ],
					 borderColor: [
						 'rgba(255,99,132,1)',
						 'rgba(54, 162, 235, 1)',
						 'rgba(255, 206, 86, 1)',
						 'rgba(75, 192, 192, 1)',
						 'rgba(153, 102, 255, 1)',
						 'rgba(255, 159, 64, 1)'
					 ],
				 }
			 ]
			 this.renderChart({labels: keys.map(x => x.substring(0, 30)).slice(0, 10),
							   datasets: dset, borderWidth: 1},
							  this.options)
		 },
		 updateKey: function(key){
			 this.lKey = key;
			 this.getAccessLogsJson();
		 },
	 },
	 mounted: function(){
		 this.lKey = this.labelsKey;
		 this.getAccessLogsJson();
	 }
 }
</script>
