<script>
	import { page } from '$app/state';
	import { fade, fly, slide } from 'svelte/transition';
	import { ctx, store } from '$lib/js/store.svelte.js';

	let { breeder, district, pairs, year } = $props();
	let authorized = $derived( ctx.user && ctx.user.admin )

	$inspect( 'BPs', breeder, district, pairs, year );

</script>

{#if breeder && district && pairs}
	<!--h2 class='header'>Alle Artikel zum Zuchtbuch</h2-->
	<!--h3 class=''>{breeder.firstname}</h3-->
	{#if authorized}
		<div class='flex flex-row justify-end pt-2'>
			<a href={`${page.url.href}/pair/0`} title='Stammm/Paar hinzufügen'>[+]</a>
		</div>
	{/if}

	{#if pairs.length > 0}
		{#each pairs as pair, i}
			<li class='flex flex-row gap-x-2'>
				<a class='grow' href={`${page.url.href}/pair/${pair.id}`} >
					<div class='w-8 text-right'>{i+1}</div>
					<div class='w-2'>:</div>
					<div class='w-8 text-right'>{pair.year}</div>
					<div class='w-16 text-center'>{pair.name}</div>
					<div class='grow'>
						{pair.breed.breedName}
						<sup class='w-32'>{pair.breedId}</sup>
					</div>
				</a>
			</li>
		{/each}
	{:else}
		<div class='px-2'>
			Noch keine Stamme/Paare eingegeben
		</div>
	{/if}
{:else}
	Keine Stämme oder Paare gefunden
{/if}


<style>
    li a {
        @apply flex flex-row border-b p-2 gap-x-2;
    }
    ol {
        @apply px-6 py-4;
    }
</style>



