<script>
	import {app} from '$lib/js/store.svelte.js';
	import Form from '$lib/cmp/form/Form.svelte';

	import PairHead from './form/PairHead.svelte';
	import Breed from './form/Breed.svelte';
	import Parents from './form/Parents.svelte';
	import Lay from './form/Lay.svelte';
	import Broods from './form/Broods.svelte';
	import Show from './form/Show.svelte';
	import Notes from './form/Notes.svelte';

	let { pair=$bindable(), standard } = $props();

	function onSubmit() {
		//let t = pair;
		//console.log( 'Pair Submit', t );
	}

</script>

{#if standard && pair}
	<Form class='flex flex-col gap-y-4' autosubmit={true} onsubmit={onSubmit}>
		<PairHead bind:pair />
		<Breed    bind:pair {standard} />
		<fieldset class='flex flex-col border-0 gap-y-4' disabled={pair.sectionId === null}>
			<Parents  bind:parents={pair.parents} bind:pair {standard}/>
			{#if pair.sectionId !== 5}
				<Lay  bind:lay={pair.lay} bind:pair {standard} />
			{/if}
			<Broods   bind:pair {standard} />
			<Show     bind:pair />
			<Notes    bind:pair />
		</fieldset>
	</Form>
{/if}

<style>

</style>