<script>
	import LayBar from '$lib/cmp/report/view/chart/LayBar.svelte';
	import BroodBarLayers from '$lib/cmp/report/view/chart/BroodBarLayers.svelte';
	import BroodBarPigeons from '$lib/cmp/report/view/chart/BroodBarPigeons.svelte';
	import ShowBar from '$lib/cmp/report/view/chart/ShowBar.svelte';

	let { district, year, data } = $props();

</script>


<div class='flex flex-col' open>
	<!--header class='border-header bg-header text-header'>Gesamt Leistungen im {district.name} in {year}</header-->
	<div class='flex flex-row flex-wrap justify-evenly py-4'>
		<LayBar class='' report={data} />
		<div class='flex flex-row flex-wrap justify-evenly'>
			<BroodBarLayers  report={data} />
			<BroodBarPigeons report={data} />
		</div>
		<ShowBar         report={data} />
	</div>
</div>

<style>
    header {
        @apply px-4 py-2 text-lg font-bold text-center sticky top-1;
    }
</style>