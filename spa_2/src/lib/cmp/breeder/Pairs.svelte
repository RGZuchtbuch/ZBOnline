<script>
	import { page } from '$app/state';

	import { ctx } from '$lib/js/store.svelte.js';

	let { breeder } = $props();
	let authorized = $derived( ctx.user && ctx.user.admin )

</script>

<section>
	{#if authorized}
		<div class='flex flex-row justify-end pt-2'>
			<a href={`${page.url.pathname}/0`} title='Stammm/Paar hinzufügen'>[+]</a>
		</div>
	{/if}
	{#if ctx.pairs && ctx.pairs.length > 0}
		{#each ctx.pairs as pair, i}
			<li class='flex flex-row gap-x-2'>
				<a class='grow' href={`${page.url.pathname}/${pair.id}`} >
					<div class='w-8 text-right'>{i+1}</div>
					<div class='w-2'>:</div>
					<div class='w-8 text-right'>{pair.year}</div>
					<div class='w-16 text-center'>{pair.name}</div>
					<div class='w-96'>
						{pair.breed.name}
						<!--sup class='w-32'>{pair.breed.id}</sup-->
					</div>
					<div class='grow'>
						{pair.color.name}
						<!--sup-- class='w-32'>{pair.color.id}</sup-->
					</div>

					<span class='text-red-600' class:accepted={pair.accepted}>{pair.accepted ? '✓' : '✗'}</span>
				</a>
			</li>
		{/each}
	{:else}
		<div class='px-2'>
			Noch keine Stamme/Paare eingegeben
		</div>
	{/if}

</section>

<style>
    li a {
        @apply flex flex-row border-b p-2 gap-x-2;
    }
    ol {
        @apply px-6 py-4;
    }

    .accepted {
	    @apply text-green-600;
    }
</style>



