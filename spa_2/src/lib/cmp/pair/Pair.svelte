<script>
	import { getContext } from 'svelte';
	import { goto } from '$app/navigation';
	import { navigating } from '$app/state';
	import api from '$lib/js/api.js.obs';

	import Form from '$lib/cmp/form/Form.svelte';
	import PairHead from './form/PairHead.svelte';
	import Breed from './form/Breed.svelte';
	import Parents from './form/Parents.svelte';
	import Lay from './form/Lay.svelte';
	import Broods from './form/Broods.svelte';
	import Show from './form/Show.svelte';
	import Notes from './form/Notes.svelte';

	let breeder  = getContext( 'breeder' );
	let district = getContext( 'district' );
	let page     = getContext( 'page' );
	let pair     = getContext( 'pair' );
	let standard = getContext( 'standard' );

	async function onSubmit() {
		console.log( 'Pair Submit' );
		if( pair.name ) {
			if( pair.id > 0 ) { // existing pair
				console.log('Put' );
				let put = await api.pair.put( pair.id, pair );
				if( put ) changed = false;
			} else { // new pair
				console.log( 'Post' );
				let post = await api.pair.post( pair );
				if( post ) changed = false;
			}
		} else if( pair.id > 0 && pair.delete ){ // name is null and delete
			//let del = await api.pair.delete( pair.id );
			//if( del ) {
				console.log( 'Delete' )
			//	pair.id = null;
				//goto( navigating.from );
			//}
		}
	}

	$effect( () => {
		pair.delete = pair.name ? false : pair.delete;
	})

	console.log( 'Nav', navigating );

</script>

{#if standard && pair}
	<Form class='flex flex-col p-4 gap-y-4' autosubmit={true} onsubmit={onSubmit}>
		<PairHead bind:pair />
		<Breed    bind:pair {standard} />
		<fieldset class='flex flex-col border-0 gap-y-4' disabled={pair.sectionId === null}>
			<Parents  bind:parents={pair.parents} bind:pair {standard}/>
			{#if pair.sectionId !== 5}
				<Lay  bind:pair {standard} />
			{/if}
			<Broods   bind:pair {standard} />
			<Show     bind:pair />
			<Notes    bind:pair={pair} />
		</fieldset>
	</Form>
{/if}

<style>

</style>