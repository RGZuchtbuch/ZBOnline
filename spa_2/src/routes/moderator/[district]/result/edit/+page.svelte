<script>
	import {page} from '$app/state';
	import { ctx, store } from '$lib/js/store.svelte.js';
	//import model from '$lib/js/model.js';
	import ResultsEdit from '$lib/cmp/moderator/district/ResultsEdit.svelte';
	import { invalidate } from '$app/navigation';
	import {onDestroy, onMount} from 'svelte';

	let { data } = $props();

	$effect( () => {
		ctx.district = data.district;
		ctx.year = data.year;
		ctx.group = data.group;
		ctx.section = data.section;
		ctx.results = data.results;
	})

	$effect( async () => {
		ctx.header = {
			title: `${ctx.district.name}`,
			menu: {
				trail: [
					{name: 'Home', href: '/'},
					{name: 'Obmann', href: '/moderator'},
					{name: data.district.short, href: `/moderator/${ctx.district.id}`},
					{name: 'Eingaben', href: `/moderator/${ctx.district.id}/result?year=${ctx.year}`},
					{name: 'Bearbeiten'},
				],
				options: [
					{name: 'Züchter', href: `/moderator/${ctx.district.id}/breeder`},
				],
			},
		};
	})

	onDestroy( () => {
		//invalidate( 'result' ); //( 'app:changed' );
	})

	console.log( 'edit page')
	//invalidateAll();//( `moderator/${data.district.id}/result/${data.year}` );


</script>


<ResultsEdit district={ctx.district} year={ctx.year} section={ctx.section} group={ctx.group} results={ctx.results} standard={ctx.standard} />

