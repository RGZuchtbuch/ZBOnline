<script>
	//import { store } from '$lib/js/store.svelte.js';

	import api from '$lib/js/api.js';
	import { email_icon, link_icon } from '$lib/cmp/icons.svelte';
	import { name } from '$lib/cmp/snippets.svelte';

	let { root } = $props();
</script>

<h3>
	Verbände im BDRG Zuchtbuch
</h3>



{#snippet row( district, level )}
	<li>
		<div class='flex flex-row grow py-2 border-b'>
			<div class='district'>{district.name}</div>
			<div class='moderator'>{@render name( district.moderator )}</div>
			<div class='email print:hidden'>
				<a href={`/district/${district.id}/email`}>L</a>
			</div>
			<div class='link print:hidden'>
				<a href={district.url}>M</a>
			</div>
		</div>
		{#if district.children}
			<ul>
				{#each district.children as child}
					{@render row( child, level+1 ) }
				{/each}
			</ul>
		{/if}
	</li>
{/snippet}

{#if root}
	<section class=''>
		<div class='header'>
			<div class='district'>Verbände </div>
			<div class='moderator'>Obmann</div>
			<div class='email' title='Email schicken'>{@render email_icon()}</div>
			<div class='link' title='Website besuchen'>{@render link_icon()}</div>
		</div>

		<ul>
			{@render row( root, 0 )}
		</ul>
	</section>
{/if}

<style>
    section {
        @apply flex flex-col m-4 border border-gray-600 rounded;
    }
    li {
        @apply pl-4 whitespace-nowrap;
    }
    .header {
        @apply flex flex-row bg-teal-200 p-2;
    }
    .district {
	    @apply grow;
    }
    .moderator {
	    @apply w-64;
    }
    .email {
        @apply w-12;
    }
    .link {
        @apply w-12;
    }

</style>