<script>
	import {fade, slide} from 'svelte/transition';
	import { NumberInput, RingInput, Select, TextInput, validator } from '../../form/Form.svelte';
	import Parent from './Parent.svelte';

	let { pair } = $props();
	//localParents.push( { id:0, sex:'0.1', ring:null, score:null, parentsPairId:null } );

	// const validate = {
	// 	ring: (v) => validator(v).ring().orNull().isValid(),
	// }

	// function updateScore() { // 1.4
	// 	const sires = data.pair.parents.reduce( ( accu, parent ) => accu + ( parent.sex ==='1.0' ? 1 : 0 ), 0 );
	// 	const dames = data.pair.parents.reduce( ( accu, parent ) => accu + ( parent.sex ==='0.1' ? 1 : 0 ), 0 );
	// 	data.pair.parents.score = `${sires}.${dames}`;
	// }

	function addParent(event) {
		console.log( 'E', event );
		const parent = { id:0, pairId:pair.id, sex:'0.1', ring:null, score:null, parentsPairId:null };
		pair.parents.push( parent );
//		data.pair.parents = [ ...data.pair.parents, parent ];
	}


	//$inspect( 'data Ps', data.pair.parents );
	$inspect( 'Ps', pair.parents );
</script>


<fieldset class='flex flex-col gap-x-2 border p-2' in:slide>
	<legend>Abstammung</legend>
	{#each pair.parents as parent, i (i) }
		<Parent {parent} {i} />
	{/each}
	<button class='w-6' type='button' onclick={addParent}>+</button>
</fieldset>
