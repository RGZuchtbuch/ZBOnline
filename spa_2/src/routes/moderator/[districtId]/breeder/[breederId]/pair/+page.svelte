<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';

	let { data } = $props();

	$effect( () => {
		if( app.breeder && app.district) {
			app.title = `Stämme von Züchter ${app.breeder.firstname} ${app.breeder.infix} ${app.breeder.lastname}`;
			app.menu.trail = [
				{ name:'Home',              href:'/' },
				{ name:'Obmann',            href:'/moderator' },
				{ name:app.district.short,  href:'/moderator/'+app.district.id },
				{ name:'Zuechter',          href:'/moderator/'+app.district.id+'/breeder' },
				{ name:`${app.breeder.firstname.charAt(0)}.${app.breeder.lastname.charAt(0)}`, href:'/moderator/'+app.district.id+'/breeder/'+app.breeder.id },
				{ name:'Stamm',             href:page.url.pathname },
			];
			app.menu.options = []

		}
	})

	console.log('Pairs page');

</script>

{#if app.district && app.breeder && app.pairs}
	<h3 class='text-center italic'>
		Stämme {app.pairs.length}
	</h3>
	<ul>
		{#each app.pairs as pair}
			<li><a href={`/moderator/${app.district.id}/breeder/${app.breeder.id}/pair/${pair.id}`}>{pair.name}</a></li>
		{/each}
	</ul>
{/if}