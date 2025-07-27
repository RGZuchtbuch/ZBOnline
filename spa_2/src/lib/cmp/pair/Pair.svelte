<script>

	import { goto } from '$app/navigation';
	import { navigating } from '$app/state';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Form, { CheckBox } from '$lib/cmp/form/Form.svelte';
	import PairHead from './form/PairHead.svelte';
	import Breed from './form/Breed.svelte';
	import Parents from './form/Parents.svelte';
	import Lay from './form/Lay.svelte';
	import Broods from './form/Broods.svelte';
	import Show from './form/Show.svelte';
	import Notes from './form/Notes.svelte';

	let { breeder, pair } = $props();

	let edit = $state( pair.id === 0 );
	let remove = $state( false );
	let changed = false; // for invalidating load article
	let authorized = $derived( ctx.user && ( ctx.user.moderator.includes( pair.districtId ) || ctx.user.admin ) ); // can edit

	async function onSubmit() {
		console.log( 'Pair Submit' );
		dirty.pairs = true;
		if( pair.breederId && pair.year && pair.name && pair.group && pair.sectionId && pair.breedId && pair.colorId ) {
			return await model.Pair.save( pair );
			//if( saved ) changed = false;
		} else if( pair.id > 0 && pair.name === null && pair.delete ){ // name is null and delete
			const ok = model.Pair.delete( pair.id );
			if( ok ) {
				await goto(`/breeder/${pair.breederId}/pair`);
			}
			return ok;
		}
		return false;
	}

	$effect( () => {
		if( pair ) {
			pair.delete = pair.name ? false : pair.delete;
		}
	})

</script>

{#if ctx.standard && pair}
	<div class='flex flex-row items-center justify-end gap-x-2 p-2 -mb-2 print:hidden'>
		<span class='meta'></span>
		{#if authorized }
			<span class='print:hidden'>
				<CheckBox label='Ändern' error='' bind:value={edit} />
			</span>
		{/if}
	</div>
	<Form class='flex flex-col px-4 py-0 gap-y-4' autosubmit={true} onsubmit={onSubmit} disabled={!edit}>
		<PairHead {breeder} {pair} />
		<Breed    {pair} standard={ctx.standard} />
		{#if pair.sectionId === 5 && pair.breedId > 0}
			<Parents  {pair} parents={pair.parents} />
			<Broods   {pair} standard={ctx.standard} />
			<Show     {pair} />
			<Notes    {pair} />
		{:else if pair.sectionId > 0 && pair.colorId > 0}
			<Parents  {pair} parents={pair.parents} />
			<Lay      {pair} standard={ctx.standard} />
			<Broods   {pair} standard={ctx.standard} />
			<Show     {pair} />
			<Notes    {pair} />
		{:else}
			<div>Weitere Eingaben nach Wahl der Rasse und für Hühner der Farbenschlag</div>
		{/if}
	</Form>
{/if}

<style>

</style>