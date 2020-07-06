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
	<tags></tags>
  </div>
</div>
</template>
<script>
import comments from './CommentList';
import searchArea from './SearchArea';
import tags from './TagList';
export default{
	components: {
		comments,
		searchArea,
		tags,
	}
}
</script>
