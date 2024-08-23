<script>

	import Page from '../common/Page.svelte';
	import {getContext} from 'svelte';
	import api from '../../js/api.js';

	import {standard} from '../../js/store.js';

	let breeder = getContext( 'breeder' );
	let pairs = [];

	function onChange() {
		console.log('Trend onChange' );
		api.breeder.pairs.get( $breeder.id ).then(response => {
			pairs = response.pairs;
			console.log( 'P', pairs );
		})
	}

	$: onChange( $breeder )

	console.log( 'Trend for ', $breeder)
</script>

<Page>
	<div slot='title'>Test</div>
	<div slot='body'>Body</div>
</Page>

<style>

</style>