<script>
	import {fade, slide} from 'svelte/transition';
	import store from '$lib/js/store.svelte.js';
	import { NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	import {dec} from '$lib/js/toolbox.js';
	import {onMount} from 'svelte';

	import ParentLayer from './Parent.Layer.svelte';
	import ParentPigeon from './Parent.Pigeon.svelte';

	let {pair=$bindable() } = $props();

	$effect( () => {
		if( pair.sectionId ) {
			if ( ! pair.parents ) {
				pair.parents = [];
			}
			const n = pair.sectionId === 5 ? 2 : 5;
			for (let i = pair.parents.length; i < n; i++) {
				addParent(i); // for knowing 1.0 or 0.1
			}
		}
	});

	function addParent( i ) {
		pair.parents.push(
			{ id:0, pairId:pair.id, sex:i===0?'1.0':'0.1', ring:null, score:null, parentsPairId:null }
		);
	}

	function newParent( i ) {
		return ;
	}

	function onAddParent( event ) {
		addParent( pair.parents.length );
	}

	onMount( () => {
		//addParentTemplates();
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
	<legend>Abstammung ( {dec( pair.parentsGrade, 1 )} ) {pair.parents.length}</legend>

	{#if pair.colorId }
		<div transition:slide>
			{#each pair.parents as parent, i (i) }
				{#if pair.sectionId === 5}
					<ParentPigeon bind:parent={pair.parents[i]} {pair} {i} />
				{:else}
					<ParentLayer bind:parent={pair.parents[i]} {pair} {i} />
				{/if}
			{/each}
			<hr>
			<div class='flex flex-row py-2'>
				<button class='w-6 h-6' type='button' onclick={onAddParent}>+</button>
				<span class='grow'></span>
				<NumberInput class='w-14 font-bold' label='G.Note' value={dec( pair.parentsGrade, 1 )} disabled />
			</div>
		</div>
	{/if}
</fieldset>
