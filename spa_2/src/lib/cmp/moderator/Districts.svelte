<script>
	import { getContext } from 'svelte';

	let { districts } = $props();

	console.log( districts.length );
</script>

<h3>
	Verbände zum Verwalten !
</h3>

{#if districts}
	<ul class='mx-8'>
		{#each districts as district, i}
			<li>
				<a href={`/moderator/${district.id}`}>{district.name}</a>
			</li>
		{/each}
	</ul>
{/if}

<style>
    table, th, td {
        @apply border border-gray-600;
    }
    th, td {
        @apply p-2 whitespace-nowrap;
    }
    a:hover {
	    @apply bg-inherit text-rose-800;
    }

</style>