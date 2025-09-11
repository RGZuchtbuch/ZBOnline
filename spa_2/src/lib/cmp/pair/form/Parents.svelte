<script>
	import { page } from '$app/state';
	import {onMount} from 'svelte';
	import {fade, slide} from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';
	import {dec} from '$lib/js/tools.js';
	import model from '$lib/js/model.js';

	import { NumberInput, RingInput, Select, Status, TextInput, validator } from '../../form/Form.svelte';
	import ParentLayer from './Parent.Layer.svelte';
	import ParentPigeon from './Parent.Pigeon.svelte';

	let { pair=$bindable() } = $props();

	// fillParents(); // initial
	//
	// onMount( () => { // if chn
	// 	if( pair ) fillParents();
	// })

	let filledParents = $state( 1 );

	$effect( () => {
		let count = 0;
		let sum = null;
		for( let parent of pair.parents ) {
			count += parent.grade ? 1 : 0;
			sum += parent.grade;
		}
		pair.parentsGrade = count > 0 ? sum / count : null;
	})

	function onAddParent( event ) {
		pair.parents.push( model.Pair.newParent( pair ) );
	}



</script>


<fieldset class='flex flex-col gap-x-2 border pt-2 px-2' in:slide>
	<legend>Abstammung <Status /></legend>
	{#if filledParents }
		{#if pair.sectionId === cfg.pigeons }
			<div transition:slide>
				{#each pair.parents as parent, i }
					{#key pair.parents[i]}
						<ParentPigeon bind:parent={pair.parents[i]} {pair} {i} />
					{/key}
				{/each}
			</div>
		{:else}
			<div transition:slide>
				{#each pair.parents as parent, i }
					{#key pair.parents[i]}
						<ParentLayer bind:parent={pair.parents[i]} {pair} {i} />
					{/key}
				{/each}
				<hr>
				<div class='flex flex-row py-2'>
					<button class='w-6 h-6 print:hidden' type='button' onclick={onAddParent}>+</button>
					<span class='grow'></span>
					<NumberInput class='w-14 font-bold' label='G.Note' value={dec( pair.parentsGrade, 1 )} title='Gesamt Abstammungsnote' disabled />
				</div>
			</div>
		{/if}
	{/if}
</fieldset>
