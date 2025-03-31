<script>
	import { email_icon, link_icon } from '$lib/cmp/icons.svelte';
	import { name } from '$lib/cmp/snippets.svelte';

	let { district } = $props();
</script>

<section>
	{#if district}
		<div class='flex flex-row header sticky top-0'>
			<div class='district'>Verbände </div>
			<div class='moderator'>Obmann</div>
			<div class='email' title='Email schicken'>{@render email_icon()}</div>
			<div class='link' title='Website besuchen'>{@render link_icon()}</div>
		</div>

		<ul>
			{@render row( district, 0 )}
		</ul>
	{/if}
</section>

{#snippet row( district, level )}
	<li>
		<div class='flex flex-row grow py-2 border-b rounded-b-none'>
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


<style>
    section {
        @apply flex flex-col my-2 border;
    }
    li {
        @apply pl-4 whitespace-nowrap;
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