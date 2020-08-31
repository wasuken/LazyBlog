<template>
<div>
  <!-- 左 -->
  <div class="box" style="float:left;width: 25%;">
	<comments></comments>
  </div>
  <!-- 中央 -->
  <div class="box" style="float:left;margin-left: 30px;width: 40%;">
	<searchArea></searchArea>
  </div>
  <!-- 右 -->
  <div class="box" style="float:left;margin-left: 30px; width: 30%;">
	<div class="box">
	  <tags></tags>
	</div>
	<div class="box">
	  <miniSR title="Top 10 Most Popular" query="sortKey=pageView&order=desc&count=10"></miniSR>
	</div>
	<div class="box">
	  <miniSR title="Top 10 Most Popular Weekly" :query="concatWeeklyStr('sortKey=pageView&order=desc&count=10')"></miniSR>
	</div>
  </div>
</div>
</template>
<script>
import comments from './CommentList';
import searchArea from './SearchArea';
import tags from './TagList';
import miniSR from './MiniSearchResult';
export default{
	components: {
		comments,
		searchArea,
		tags,
		miniSR,
	},
	methods:{
		concatWeeklyStr: function(query){
			let before = new Date();
			let after = new Date();
			before.setDate(before.getDate() - 7);
			let bDateFmt = [before.getFullYear(),
							("0" + (before.getMonth() + 1)).slice(-2),
							("0" + before.getDate()).slice(-2)].join("-");
			after.setDate(after.getDate() + 1);
			let aDateFmt = [after.getFullYear(),
							("0" + (after.getMonth() + 1)).slice(-2),
							("0" + after.getDate()).slice(-2)].join("-");
			return query + "&pb=" + bDateFmt + "&pe=" + aDateFmt;
		}
	}
}
</script>
