<script>
	import LayBar from '$lib/cmp/report/view/chart/LayBar.svelte';
	import BroodBarLayers from '$lib/cmp/report/view/chart/BroodBarLayers.svelte';
	import BroodBarPigeons from '$lib/cmp/report/view/chart/BroodBarPigeons.svelte';
	import ShowBar from '$lib/cmp/report/view/chart/ShowBar.svelte';

	let { district, report, year } = $props();

</script>


<div class='flex flex-col' open>
	<header class=''>Gesamt Leistungen im {district.name} in {year}</header>
	<div class='flex flex-row justify-evenly py-4'>
		<LayBar class='' report={report} />
		<BroodBarLayers  report={report} />
		<BroodBarPigeons report={report} />
		<ShowBar         report={report} />
	</div>
</div>

<style>
    header {
        @apply px-4 py-2 bg-teal-200 text-lg font-bold sticky top-0 text-center;
    }
</style>