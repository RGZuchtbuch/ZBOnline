<script>
	import {page} from '$app/state';
	import { ctx, store } from '$lib/js/store.svelte.js';
	//import model from '$lib/js/model.js';
	import ResultsEdit from '$lib/cmp/moderator/district/ResultsEdit.svelte';
	import { invalidate } from '$app/navigation';
	import {onDestroy, onMount} from 'svelte';

	let { data } = $props();

	// ctx.year = null;
	// ctx.group = null;
	// ctx.section = null;
	// ctx.results = null;
	ctx.year = data.year;
	ctx.group = data.group;
	ctx.section = data.section;
	ctx.results = data.results;

	$effect( () => {
		// ctx.year = data.year;
		// ctx.group = data.group;
		// ctx.section = data.section;
		// ctx.results = data.results;
	})

	$effect( async () => {
		ctx.header = {
			title: `${data.district.name}`,
			menu: {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Obmann', href: '/moderator'},
					{name: data.district.short, href: `/moderator/${data.district.id}`},
					{name: 'Eingaben', href: `/moderator/${data.district.id}/result?year=${data.year}`},
					{name: 'Eingeben'},
				],
				options: [
					{name: 'Eingaben', href: `/moderator/${data.district.id}/result?year=${data.year}`},
					{name: 'Züchter', href: `/moderator/${data.district.id}/breeder`},
				],
			},
		};
	})

	onDestroy( () => {
		//invalidate( 'result' ); //( 'app:changed' );
	})

	console.log( 'edit page')

</script>

{#if data.district && data.year && data.section && data.group && data.results } <!-- needed as ctx might not be updated yet -->
	<ResultsEdit district={data.district} year={data.year} section={data.section} group={data.group} results={data.results} standard={ctx.standard} />
{/if}
