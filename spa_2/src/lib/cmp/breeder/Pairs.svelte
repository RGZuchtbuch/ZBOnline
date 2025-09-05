<script>
	import { page } from '$app/state';

	import { ctx } from '$lib/js/store.svelte.js';

	let { breeder } = $props();
	let authorized = $derived( ctx.user && ctx.user.admin )

</script>

<section>
	{#if authorized}
		<div class='flex flex-row justify-end p-2'>
			<a class='w-8 border-button bg-button text-button py-0 px-2' href={`${page.url.pathname}/0`} title='Stammm/Paar hinzufügen'>✙</a>
		</div>
	{/if}
	<div class='flex flex-row p-2 gap-x-2 border-header bg-header text-header py-0 px-2'>
		<span class='w-8 text-right'>#</span>
		<span class='w-2 text-center'>:</span>
		<span class='w-8 text-center'>Jahr</span>
		<span class='w-20 text-center'>Stamm</span>
		<span class='w-96 '>Rasse</span>
		<span class='grow'>Farbenschlag</span>
		<span class='w-8 text-center'>Ok</span>
	</div>
	{#if ctx.pairs && ctx.pairs.length > 0}
		{#each ctx.pairs as pair, i}
			<a class='grow flex flex-row gap-x-2 p-2' href={`${page.url.pathname}/${pair.id}`} >
				<span class='w-8 text-right'>{i+1}</span>
				<span class='w-2'>:</span>
				<span class='w-8 text-right'>{pair.year}</span>
				<span class='w-20 text-center'>{pair.name}</span>
				<span class='w-96'>
					{pair.breed.name}
					<!--sup class='w-32'>{pair.breed.id}</sup-->
				</span>
				<span class='grow'>
					{pair.color.name}
					<!--sup-- class='w-32'>{pair.color.id}</sup-->
				</span>

				<span class='w-8 text-center text-red-600' class:accepted={pair.accepted} title='Ok vom Obmann ?'>
					{pair.accepted ? '✓' : '✗'}
				</span>
			</a>
		{/each}
	{:else}
		<div class='px-2'>
			Noch keine Stamme/Paare eingegeben
		</div>
	{/if}

</section>

<style>
    .accepted {
	    @apply text-green-600;
    }
</style>



