<template>
	<GChart
		:type="chartType"
				   :data="chartData"
							  :options="chartOptions"
											:createChart="
											(el, google, type) => {
												return new google.visualization[type](el)
											}
											"
	/>
</template>

<script>
 import { GChart } from 'vue-google-charts'
 export default {
	 components: {
		 GChart,
	 },
	 props: {
		 chartType: {
			 type: String,
			 default: ''
		 },
		 chartData: {
			 type: Array,
			 default: () => {
				 return []
			 }
		 },
		 chartOptions: {
			 type: Object,
			 default: () => {
				 return {}
			 }
		 }
	 }
 }
</script>
