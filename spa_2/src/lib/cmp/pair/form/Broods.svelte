<script>
	import { fade, slide } from 'svelte/transition';
	import { NumberInput } from '$lib/cmp/form/Form.svelte';
	import BroodLayer from './Brood.Layer.svelte';
	import BroodPigeon from './Brood.Pigeon.svelte';
	import aab from '$lib/js/aab.js';
	import {dec} from '$lib/js/toolbox.js';

	let { pair, standard } = $props();

	if( ! pair.broods ) {
		pair.broods = [];
	}
	for( let i=pair.broods.length; i<4; i++ ) {
		pair.broods.push( newBrood() );
	}

	function newBrood() {
		return { id:0, pairId:pair.id, start:null, eggs:null, fertile:null, hatched:null }
	}

	function addBrood() {
		const brood = newBrood();
		pair.broods.push( brood );
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

		if( pair.sectionId === 5 ) {
			pair.broodGrade = aab.brood.pigeon(pair.breed.broodGroup, broods, hatched);
		} else {
			pair.broodGrade = aab.brood.layer(eggs, hatched);
		}
		console.log('Broods', pair.sectionId, pair.broodGrade, broods, hatched, pair.breed.broodGroup );
		console.log('B', pair.breed );
	})

</script>


<fieldset class='flex flex-col gap-x-2 border pt-2 px-2' in:fade>
	<legend>Brutleistung</legend>
	{#each pair.broods as brood, i}
		{#if pair.sectionId === 5 }
			<BroodPigeon {brood} {pair} {standard} {i}/>
		{:else}
			<BroodLayer {brood} {pair} {standard} {i}/>
		{/if}
	{/each}
	<div class='border-t flex flex-row pt-1'>
		<button class='w-6 h-6' type='button' onclick={addBrood}>+</button>
		<span class='grow'></span>
		<NumberInput class='w-14 font-bold' label='G.Note' value={dec( pair.broodGrade, 1 )} disabled/>
	</div>
</fieldset>
