<script>
	//import { store } from '$lib/js/store.svelte.js';

	import api from '$lib/js/api.js';
	import { email_icon, link_icon } from '$lib/cmp/icons.svelte';
	import { name } from '$lib/cmp/snippets.svelte';

	let { root } = $props();
</script>

<h3>
	Verband und Zuchtbuchbmann
</h3>



{#snippet row( district, level )}
	<tr>
		{#each { length:level }, n }
			<td>.</td>
		{/each}
		<td>→</td>
		<td colspan={5-level}>{district.name}</td>
		<td>{@render name( district.moderator )}</td>
		<td class='print:hidden'>
			<a href='/'>{@render email_icon()}</a>
		</td>
		<td class='print:hidden'>
			<a href='/'>{@render link_icon()}</a>
		</td>
	</tr>
	{#each district.children as child}
		{@render row( child, level+1 ) }
	{/each}
{/snippet}

{#if root}
	<table class='mx-8 table-auto'>
		<thead>
		<tr><th colspan='4'>Verbände </th></tr>
		</thead>

		<tbody>
			{@render row( root, 0 )}
		</tbody>
	</table>
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