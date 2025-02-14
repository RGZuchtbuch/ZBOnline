<script>
	import {app} from '$lib/js/store.svelte.js';
	import Form from '$lib/cmp/form/Form.svelte';

	import PairHead from './form/PairHead.svelte';
	import Breed from './form/Breed.svelte';
	import Parents from './form/Parents.svelte';
	import Lay from './form/Lay.svelte';
	import Broods from './form/Broods.svelte';
	import Show from './form/Show.svelte';

	function onSubmit() {
		console.log( 'Pair Submit', app.pair );
	}
</script>

{#if app.standard && app.pair}
	<Form class='flex flex-col gap-y-2' autosubmit={true} onsubmit={onSubmit}>
		<PairHead pair={app.pair} />
		<Breed    pair={app.pair} />
		<Parents  pair={app.pair} />
		<Lay      pair={app.pair} />
		<Broods   pair={app.pair} />
		<Show     pair={app.pair} />
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