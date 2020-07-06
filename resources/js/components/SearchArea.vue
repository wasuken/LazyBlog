<template>
<div>
  <box />
  <hr/>
  <result />
</div>
</template>
<script>
import box from './SearchBox.vue'
import result from './SearchResult.vue'
export default {
	components: {
		box,
		result,
	}
}
</script>
