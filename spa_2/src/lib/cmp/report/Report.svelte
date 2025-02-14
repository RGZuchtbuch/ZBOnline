<script>
	import {fade} from 'svelte/transition';
	import Layout from '$lib/cmp/Page.svelte';
	import SectionPie from '$lib/cmp/report/SectionPie.svelte';
	import Filter from '$lib/cmp/report/Filter.svelte';
	import Trend from '$lib/cmp/report/Trend.svelte';
	import Map from '$lib/cmp/report/Map.svelte';
	import ResultsBar from '$lib/cmp/report/ResultsBar.svelte';
	import ResultsTable from '$lib/cmp/report/ResultsTable.svelte';

</script>

<Layout>
	<h1 slot='title' class='title'>Zuchtleistungen</h1>

	<menu slot='content' class='content flex flex-col gap-y-2' in:fade>

		<Filter class='print:hidden'/>
		<SectionPie />
		<ResultsBar />

		<div class='print:hidden'>Select leistung</div>
		<div class='flex flex-row justify-evenly gap-2 print:break-after-page'>
			<Trend /> <Map />
		</div>

		<ResultsTable />
	</menu>

	<footer slot='footer' class='footer' in:fade>Written by Eelco</footer>

</Layout>
