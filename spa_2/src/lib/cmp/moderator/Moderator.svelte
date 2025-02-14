<script>
	import { page } from '$app/stores';
</script>


<div class='flex flex-col text-center'>
	{#each $page.data.districts as district}
		<a href={'/obmann/verband/'+district.id}>{district.name}</a>
	{/each}
</div>

<style>
</style>