<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';
	import { email_icon, link_icon, fullname } from '$lib/cmp/snippets.svelte';

	let { root } = $props();
	let moderatables = $state( [] );

	addDistrict( root, moderatables );
	moderatables.reverse();

	function addDistrict( district, list ) {

		for( const child of district.children ) {
			addDistrict( child, list );
		}
		if( app.user.moderates.includes( district.id ) ) {
			list.push( district );
			console.log( 'AD', district.name );
		}
	}


</script>

<h3>
	Verbände zum Verwalten
</h3>



{#snippet row( districts, moderatesParent = false )}
	{#each districts as district}
		<li class='row'>
			<span>→</span>
			<span>
				<a href={`${page.url.pathname}/${district.id}`}>{district.name}</a>
			</span>
		</li>
		{#each district.children as child}
			{@render row( child ) }
		{/each}
	{/each}
{/snippet}

{#if root}
	<ul class='mx-8'>
		{@render row( moderatables )}
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