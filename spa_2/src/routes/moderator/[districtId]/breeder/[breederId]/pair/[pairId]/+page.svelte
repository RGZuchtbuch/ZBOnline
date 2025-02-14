<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';
	import api from '$lib/js/api.js';
	import {txt } from '$lib/js/toolbox.js';
	import Pair from '$lib/cmp/pair/Pair.svelte';
	import {onMount} from 'svelte';


	onMount( async () => {
		app.pair = null;
		const response = await api.pair.get( page.params.pairId );
		if( response ) {
			app.pair = response.pair;
		}
	})
	$effect( () => {
		if( app.district && app.breeder && app.pair ) {
			app.title = `Stamm ${app.pair.year}.${app.pair.name} von Züchter ${txt(app.breeder.firstname)} ${ txt(app.breeder.infix) } ${ txt(app.breeder.lastname) }`;
			app.menu.trail = [
				{ name:'Home',      href:'/' },
				{ name:'Obmann',    href:'/moderator' },
				{ name:'Verband',   href:`/moderator/${app.district.id}` },
				{ name:'Züchter',   href:`/moderator/${app.district.id}/breeder/${app.breeder.id}` },
				{ name:'Stämme',    href:`/moderator/${app.district.id}/breeder/${app.breeder.id}/pair` },
				{ name:''+app.pair.year%100+'.'+app.pair.name,    href:`/moderator/${app.district.id}/breeder/${app.breeder.id}/pair/${app.pair.id}` },
			];
			app.menu.options = [
			]

		}
	})

	$inspect( 'AP', app.pair );
</script>

{#if app.standard && app.pair }
	<Pair pair={app.pair} standard={app.standard} />
{/if}
