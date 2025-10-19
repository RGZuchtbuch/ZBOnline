<script>
	import { page } from '$app/state';
	import { goto } from '$app/navigation';
	import { dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Form, { CheckBox } from '$lib/cmp/form/Form.svelte';
	import PairHead from './form/PairHead.svelte';
	import Breed from './form/Breed.svelte';
	import Parents from './form/Parents.svelte';
	import Lay from './form/Lay.svelte';
	import Broods from './form/Broods.svelte';
	import Show from './form/Show.svelte';
	import Notes from './form/Notes.svelte';

	let { pair=$bindable(), user, standard } = $props();

	let edit = $state( pair.id === 0 );
	let authorized = $derived( user && pair && ( (user.id === pair.breeder.id && user.active) || user.moderator.includes( pair.districtId ) || user.admin ) ); // can edit

	async function onSubmit() {
		let ok = false;
		if( pair.breederId && pair.year && pair.name && pair.group && pair.sectionId && pair.breedId && ( pair.sectionId === 5 || pair.colorId ) ) {
			ok = await model.Pair.save( pair );
		} else if( pair.id > 0 && pair.name === null && pair.delete ){ // name is null and delete
			ok = model.Pair.delete( pair.id );
			if( ok ) {
				const path = page.url.pathname;
				await goto( path.slice( 0, path.lastIndexOf( '/' ) ) ); // loose pair id
			}
		}
		dirty.pairs++; // only change, to avoid loadpairs has to reset and thus retrigger
		dirty.report++;
		dirty.results++;
		return ok;
	}

	$effect( () => {
		if( pair ) {
			pair.delete = pair.name ? false : pair.delete;
		}
	})

</script>

{#if pair}
	<div class='flex flex-row items-center justify-end gap-x-2 p-2 -mb-2 print:hidden'>
		<span class='meta'></span>
		{#if authorized }
			<span class='print:hidden'>
				<CheckBox label='Ändern' error='' bind:value={edit} />
			</span>
		{/if}
	</div>

		<Form class='flex flex-col px-4 py-0 gap-y-4' autosubmit={true} onsubmit={onSubmit} disabled={!edit}>
			<PairHead bind:pair={pair} />
			<Breed    bind:pair={pair} standard={standard} />
			{#if pair.sectionId === 5 && pair.breedId > 0}
				<Parents  bind:pair={pair} {edit}/>
				<Broods   bind:pair={pair} standard={standard} {edit} />
				<Show     bind:pair={pair} />
				<Notes    bind:pair={pair} />
			{:else if pair.sectionId > 0 && pair.colorId > 0}
				<Parents  bind:pair={pair} {edit}/>
				<Lay      bind:pair={pair} standard={standard} />
				<Broods   bind:pair={pair} standard={standard} {edit}/>
				<Show     bind:pair={pair} />
				<Notes    bind:pair={pair} />
			{:else}
				<div>Weitere Eingaben nach Wahl der Rasse und für Hühner der Farbenschlag</div>
			{/if}
		</Form>
{/if}

<style>

</style>