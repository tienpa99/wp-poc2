<script lang="ts">
	export let size = 16;
	export let bg = '#069E08';
</script>

<div style="width: {size}px; height: {size}px;">
	<svg viewBox="0 0 110 110" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="M55.4 107.8C84.3397 107.8 107.8 84.3397 107.8 55.4C107.8 26.4603 84.3397 3 55.4 3C26.4603 3 3 26.4603 3 55.4C3 84.3397 26.4603 107.8 55.4 107.8Z"
			fill={bg}
		/>
		<path d="M58 46.6V97.4L84.2 46.6H58Z" fill="white" />
		<path d="M52.7 64.1V13.4L26.6 64.1H52.7Z" fill="white" />
	</svg>
</div>

<style>
	div {
		display: flex;
		align-items: center;
		justify-content: center;
	}
</style>
