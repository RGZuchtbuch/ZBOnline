<script>
	import {fade, slide} from 'svelte/transition';
	import { NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	import Parent from './Parent.svelte';
	import {dec} from '$lib/js/toolbox.js';

	let { pair, standard } = $props();

	if( ! pair.parents ) {
		pair.parents = [];
	}
	for( let i=pair.parents.length; i<4; i++ ) {
		pair.parents.push( newParent() );
	}

	function newParent() {
		return { id:0, pairId:pair.id, sex:'0.1', ring:null, score:null, parentsPairId:null };
	}

	function addParent(event) {
		const parent = newParent();
		pair.parents.push( parent );
	}

	$effect( () => {
		let count = 0;
		let sum = null;
		for( let parent of pair.parents ) {
			count += parent.grade ? 1 : 0;
			sum += parent.grade;
		}
		pair.parentsGrade = count > 0 ? sum / count : null;
	})

</script>


<fieldset class='flex flex-col gap-x-2 border pt-2 px-2' in:slide>
	<legend>Abstammung {pair.parentsGrade}</legend>
	{#each pair.parents as parent, i (i) }
		<Parent {pair} {parent} {i} {standard} />
	{/each}
	<div class='border-t flex flex-row pt-1'>
		<button class='w-6 h-6' type='button' onclick={addParent}>+</button>
		<span class='grow'></span>
		<NumberInput class='w-14 font-bold' label='G.Note' value={dec( pair.parentsGrade, 1 )} disabled/>
	</div>
</fieldset>
