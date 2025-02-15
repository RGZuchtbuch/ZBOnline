<script>
	import {app} from '$lib/js/store.svelte.js';
	import Form from '$lib/cmp/form/Form.svelte';

	import PairHead from './form/PairHead.svelte';
	import Breed from './form/Breed.svelte';
	import Parents from './form/Parents.svelte';
	import Lay from './form/Lay.svelte';
	import Broods from './form/Broods.svelte';
	import Show from './form/Show.svelte';

	let { standard, district, pair } = $props();

	function onSubmit() {
		console.log( 'Pair Submit', app.pair );
	}
</script>

{#if standard && pair}
	<Form class='flex flex-col gap-y-2' autosubmit={true} onsubmit={onSubmit}>
		<PairHead {pair} />
		<Breed    {pair} {standard}/>
		<Parents  {pair} />
		<Lay      {pair} />
		<Broods   {pair} />
		<Show     {pair} />
	</Form>
{/if}

<style>
	.tab {
        @apply p-2 border border-gray-600 rounded-t-xl rounded-b-none bg-slate-100 text-black;
    }
	.active {
		@apply border-b-white bg-white;
	}
</style>