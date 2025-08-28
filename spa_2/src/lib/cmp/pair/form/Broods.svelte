<script>
	import { fade, slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js';
	import aab from '$lib/js/aab.js';
	import {dec} from '$lib/js/tools.js';
	import { NumberInput, Status } from '$lib/cmp/form/Form.svelte';
	import BroodLayer from './Brood.Layer.svelte';
	import BroodPigeon from './Brood.Pigeon.svelte';

	let { standard, pair=$bindable() } = $props();


	let breed = $derived( standard.breeds[ pair.breedId ] );

	// if( ! pair.broods ) {
	// 	console.log('Create broods')
	// 	pair.broods = [];
	// }
	$effect( ()=> {
		for( let i=pair.broods.length; i<1; i++ ) { // minimum of 1
			addBrood();
		}
	})

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
			if( brood.eggs && brood.hatched >= 0 ) {
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
	<legend>Brutleistung <Status /></legend>

	{#if true }
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
				<button class='w-6 h-6 print:hidden' type='button' onclick={addBrood}>+</button>
				<span class='grow'></span>
				<NumberInput class='w-14 font-bold' label='G.Note' value={dec( pair.broodGrade, 1 )} title='Gesamt Brutnote' disabled/>
			</div>
		</div>
	{/if}

</fieldset>
