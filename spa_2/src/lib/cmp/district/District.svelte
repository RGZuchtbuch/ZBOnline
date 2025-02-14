<script>
	import {page} from '$app/stores';
	import Layout from '$lib/cmp/Page.svelte';
	import Breeders from '../breeder/Breeders.svelte';

</script>

	<div class='content '>
		<p>TODO, show statistics? or directly jump to breeders</p>

		Eingeben der Stammmeldungen für den Züchter
		<hr>
		.... <a href={$page.url.pathname+'/zuechter'}>Züchter</a>

	</div>


<style>

</style>