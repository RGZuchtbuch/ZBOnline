<script>

	import { goto } from '$app/navigation';
	import { navigating } from '$app/state';
	import store from '$lib/js/store.svelte.js';
	import {Pair} from '$lib/js/pair.svelte.js';

	import Form from '$lib/cmp/form/Form.svelte';
	import PairHead from './form/PairHead.svelte';
	import Breed from './form/Breed.svelte';
	import Parents from './form/Parents.svelte';
	import Lay from './form/Lay.svelte';
	import Broods from './form/Broods.svelte';
	import Show from './form/Show.svelte';
	import Notes from './form/Notes.svelte';

	let { pair } = $props();

	async function onSubmit() {
		console.log( 'Pair Submit' );
		if( pair.breederId && pair.year && pair.name && pair.group && pair.sectionId && pair.breedId && pair.colorId ) {
			return await Pair.save( pair );
			//if( saved ) changed = false;
		} else if( pair.id > 0 && pair.name === null && pair.delete ){ // name is null and delete
			console.log( 'Delete' ) // TODO pair delete
			const ok = Pair.delete( pair.id );
			if( ok ) {
				await goto(`/moderator/${pair.districtId}/breeder/${pair.breederId}/pair`);
			}
			return ok;
		}
	}

	$effect( () => {
		if( pair ) {
			pair.delete = pair.name ? false : pair.delete;
		}
	})

</script>

{#if store.standard && pair}
	<Form class='flex flex-col p-4 gap-y-4' autosubmit={true} onsubmit={onSubmit}>
		<PairHead bind:pair />
		<Breed    bind:pair standard={store.standard} />
		<fieldset class='flex flex-col border-0 gap-y-4' disabled={pair.sectionId === null}>
			<Parents  bind:parents={pair.parents} bind:pair />
			{#if pair.sectionId !== 5}
				<Lay  bind:pair standard={store.standard} />
			{/if}
			<Broods   bind:pair standard={store.standard} />
			<Show     bind:pair />
			<Notes    bind:pair={pair} />
		</fieldset>
	</Form>
{/if}

<style>

</style>