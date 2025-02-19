<script>
	import {fade, slide} from 'svelte/transition';
	import { NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	import Parent from './Parent.svelte';
	import {dec} from '$lib/js/toolbox.js';
	import {onMount} from 'svelte';

	let {pair=$bindable(), standard } = $props();


	function addParentTemplates() {
		if( ! pair.parents ) {
			pair.parents = [];
		}
		const n = pair.breedId === 5 ? 2 : 4;
		for( let i=pair.parents.length; i<n; i++ ) {
			addParent( i ); // for knowing 1.0 or 0.1
		}
	}

	function addParent(event) {
		const parent = newParent();
		pair.parents.push( parent );
	}

	function newParent( i ) {
		return { id:0, pairId:pair.id, sex:i===0?'1.0':'0.1', ring:null, score:null, parentsPairId:null };
	}

	onMount( () => {
		addParentTemplates();
	})

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
	<legend>Abstammung ( {dec( pair.parentsGrade, 1 )} )</legend>

	{#if pair.colorId }
		<div transition:slide>
			{#each pair.parents as parent, i (i) }
				<Parent bind:parent={pair.parents[i]} {pair} {standard} {i} />
			{/each}
			<hr>
			<div class='flex flex-row pt-1 pb-0'>
				<button class='w-6 h-6' type='button' onclick={addParent}>+</button>
				<span class='grow'></span>
				<NumberInput class='w-14 font-bold' label='G.Note' value={dec( pair.parentsGrade, 1 )} disabled/>
			</div>
		</div>
	{/if}
</fieldset>
