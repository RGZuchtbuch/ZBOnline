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

	//let { breeder } = $props(); using only ctx.pair
	//let pair = ctx.pair;

	let edit = $state( ctx.pair.id === 0 );
	//let remove = $state( false );
	let authorized = $derived( ctx.user && ctx.pair && ( ctx.user.moderator.includes( ctx.pair.districtId ) || ctx.user.admin ) ); // can edit

	async function onSubmit() {
		console.log( 'Pair Submit' );

		let ok = false;
		if( ctx.pair.breederId && ctx.pair.year && ctx.pair.name && ctx.pair.group && ctx.pair.sectionId && ctx.pair.breedId && ( ctx.pair.sectionId === 5 || ctx.pair.colorId ) ) {
			ok = await model.Pair.save( ctx.pair );
		} else if( ctx.pair.id > 0 && ctx.pair.name === null && ctx.pair.delete ){ // name is null and delete
			ok = model.Pair.delete( ctx.pair.id );
			if( ok ) {
				await goto(`/breeder/${ctx.pair.breederId}/pair`);
			}
		}
		dirty.pairs++; // only change, to avoid loadpairs has to reset and thus retrigger
		dirty.report++;
		dirty.results++;
		return ok;
	}

	$effect( () => {
		if( ctx.pair ) {
			ctx.pair.delete = ctx.pair.name ? false : ctx.pair.delete;
		}
	})


</script>

{#if ctx.pair}
	{ctx.pair.sectionId}
	<div class='flex flex-row items-center justify-end gap-x-2 p-2 -mb-2 print:hidden'>
		<span class='meta'></span>
		{#if authorized }
			<span class='print:hidden'>

				<CheckBox label='Ändern' error='' bind:value={edit} />
			</span>
		{/if}
	</div>
	<Form class='flex flex-col px-4 py-0 gap-y-4' autosubmit={true} onsubmit={onSubmit} disabled={!edit}>
		<PairHead bind:pair={ctx.pair} />
		<Breed    bind:pair={ctx.pair} standard={ctx.standard} />
		{#if ctx.pair.sectionId === 5 && ctx.pair.breedId > 0}
			<Parents  bind:pair={ctx.pair} />
			<Broods   bind:pair={ctx.pair} standard={ctx.standard} />
			<Show     bind:pair={ctx.pair} />
			<Notes    bind:pair={ctx.pair} />
		{:else if ctx.pair.sectionId > 0 && ctx.pair.colorId > 0}
			<Parents  bind:pair={ctx.pair} parents={ctx.pair.parents} />
			<Lay      bind:pair={ctx.pair} standard={ctx.standard} />
			<Broods   bind:pair={ctx.pair} standard={ctx.standard} />
			<Show     bind:pair={ctx.pair} />
			<Notes    bind:pair={ctx.pair} />
		{:else}
			<div>Weitere Eingaben nach Wahl der Rasse und für Hühner der Farbenschlag</div>
		{/if}
	</Form>
{/if}

<style>

</style>