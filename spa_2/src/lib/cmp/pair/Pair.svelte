<script>

	import { goto } from '$app/navigation';
	import { navigating } from '$app/state';
	import { ctx, store } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Form, { CheckBox } from '$lib/cmp/form/Form.svelte';
	import PairHead from './form/PairHead.svelte';
	import Breed from './form/Breed.svelte';
	import Parents from './form/Parents.svelte';
	import Lay from './form/Lay.svelte';
	import Broods from './form/Broods.svelte';
	import Show from './form/Show.svelte';
	import Notes from './form/Notes.svelte';

	let { pair } = $props();

	let edit = $state( false );
	let remove = $state( false );
	let changed = false; // for invalidating load article
	let authorized = $derived( ctx.user && ( ctx.user.moderator.includes( pair.districtId ) || ctx.user.admin ) ); // can edit

	async function onSubmit() {
		console.log( 'Pair Submit' );
		if( pair.breederId && pair.year && pair.name && pair.group && pair.sectionId && pair.breedId && pair.colorId ) {
			return await model.Pair.save( pair );
			//if( saved ) changed = false;
		} else if( pair.id > 0 && pair.name === null && pair.delete ){ // name is null and delete
			const ok = model.Pair.delete( pair.id );
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

{#if ctx.standard && pair}
	<div class='flex flex-row items-center justify-end gap-x-2 p-2 print:hidden'>
		<span class='meta'></span>
		{#if authorized }
			<span class='print:hidden'>
				<CheckBox label='Ändern' error='' bind:value={edit} />
			</span>
		{/if}
	</div>
	<Form class='flex flex-col p-4 gap-y-4' autosubmit={true} onsubmit={onSubmit} disabled={!edit}>
		<PairHead bind:pair />
		<Breed    bind:pair standard={ctx.standard} />
		<fieldset class='flex flex-col border-0 gap-y-4' disabled={pair.sectionId === null}>
			<Parents  bind:parents={pair.parents} bind:pair />
			{#if pair.sectionId !== 5}
				<Lay  bind:pair standard={ctx.standard} />
			{/if}
			<Broods   bind:pair standard={ctx.standard} />
			<Show     bind:pair />
			<Notes    bind:pair={pair} />
		</fieldset>
	</Form>
{/if}

<style>

</style>