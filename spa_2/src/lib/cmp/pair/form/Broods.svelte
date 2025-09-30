<script>
	import { page } from '$app/state';
	import { fade, slide } from 'svelte/transition';

	import aab from '$lib/js/aab.js';
	import {dec} from '$lib/js/tools.js';
	import model from '$lib/js/model.js';
	import { cfg } from '$lib/js/store.svelte.js';

	import { NumberInput, Status } from '$lib/cmp/form/Form.svelte';
	import BroodLayer from './Brood.Layer.svelte';
	import BroodPigeon from './Brood.Pigeon.svelte';

	let { pair=$bindable(), standard, edit } = $props();

	let breed = $derived( standard.breeds[ pair.breedId ] );

	$effect( () => {
		let broods = 0;
		let eggs = 0;
		let hatched = 0;

		for( let brood of pair.broods ) {
			if( brood.eggs && brood.hatched >= 0 ) {
				broods += 1;
				eggs += brood.eggs;
				hatched += brood.hatched;
			}
		}

		pair.broodGrade = pair.sectionId === cfg.pigeons && breed ?
			aab.brood.pigeon( breed.broodGroup, broods, hatched ) :
			pair.broodGrade = aab.brood.layer( eggs, hatched );
	})

	function onAddBrood() {
		pair.broods.push( model.Pair.newBrood( pair ) );
	}

	//$inspect( 'PB', pair.broods );

</script>


<fieldset class='flex flex-col gap-x-2 border pt-2 px-2' in:fade disabled={ ! pair.breedId }>
	<legend>Brutleistung <Status /></legend>

	{#if pair.broods.length >=2 }
		<div transition:slide>
			{#each pair.broods as brood, i }
				{#if pair.sectionId === 5 }
					<BroodPigeon bind:brood={pair.broods[i]} bind:pair={pair} {standard} {i}/>
				{:else}
					<BroodLayer  bind:brood={pair.broods[i]} bind:pair={pair} {standard} {i}/>
				{/if}
			{/each}
			<hr>
			<div class='flex flex-row pt-1'>
				{#if edit}
					<button class='w-6 h-6 print:hidden' type='button' onclick={onAddBrood}>+</button>
				{/if}
				<span class='grow'></span>
				<NumberInput class='w-14 font-bold' label='G.Note' value={dec( pair.broodGrade, 1 )} title='Gesamt Brutnote' disabled/>
			</div>
		</div>
	{/if}

</fieldset>
