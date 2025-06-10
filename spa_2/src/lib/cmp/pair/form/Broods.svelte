<script>
	import { fade, slide } from 'svelte/transition';
	import { NumberInput } from '$lib/cmp/form/Form.svelte';
	import BroodLayer from './Brood.Layer.svelte';
	import BroodPigeon from './Brood.Pigeon.svelte';
	import aab from '$lib/js/aab.js';
	import {dec} from '$lib/js/toolbox.js';

	let { pair=$bindable(), standard } = $props();

	let breed = $derived( standard.breeds[ pair.breedId ] );

	if( ! pair.broods ) {
		console.log('Create broods')
		pair.broods = [];
	}
	for( let i=pair.broods.length; i<3; i++ ) {
		addBrood();
	}

	function addBrood() {
		const brood = newBrood();
		pair.broods.push( brood );
	}

	function newBrood() {
		return { id:0, pairId:pair.id, start:null, eggs:null, fertile:null, hatched:null, chicks:[] }
	}

	$effect( () => {
		let broods = 0;
		let eggs = 0;
		let hatched = 0;

		for( let brood of pair.broods ) {
			if( brood.eggs && brood.hatched ) {
				broods += 1;
				eggs += brood.eggs;
				hatched += brood.hatched;
			}
		}

		pair.broodGrade = pair.sectionId === 5 && breed ?
			aab.brood.pigeon( breed.broodGroup, broods, hatched ) :
			pair.broodGrade = aab.brood.layer( eggs, hatched );
		// if( pair.sectionId === 5 && breed ) {
		// 	pair.broodGrade = aab.brood.pigeon( breed.broodGroup, broods, hatched);
		// 	console.log( 'PG', pair.broodGrade )
		// } else {
		// 	pair.broodGrade = aab.brood.layer(eggs, hatched);
		// }
	})

</script>


<fieldset class='flex flex-col gap-x-2 border pt-2 px-2' in:fade disabled={ ! pair.breedId }>
	<legend>Brutleistung ({pair.broodGrade}) Gruppe :{breed.broodGroup}</legend>
	{#if pair.colorId }
		<div transition:slide>
			{#each pair.broods as brood, i }
				{#if pair.sectionId === 5 }
					<BroodPigeon bind:brood={pair.broods[i]} bind:pair {standard} {i}/>
				{:else}
					<BroodLayer  bind:brood={pair.broods[i]} bind:pair {standard} {i}/>
				{/if}
			{/each}
			<hr>
			<div class='flex flex-row pt-1'>
				<button class='w-6 h-6' type='button' onclick={addBrood}>+</button>
				<span class='grow'></span>
				<NumberInput class='w-14 font-bold' label='G.Note' value={dec( pair.broodGrade, 1 )} disabled/>
			</div>
		</div>
	{/if}
</fieldset>
