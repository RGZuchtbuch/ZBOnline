<script>
	import { page } from '$app/state';
	import { app } from '$lib/js/store.svelte.js';

	let { data } = $props();

	$effect( () => {
		if( data.breeder && data.district) {
			app.title = `Stämme von Züchter ${data.breeder.firstname} ${data.breeder.infix} ${data.breeder.lastname}`;
			app.menu.trail = [
				{ name:'Home',              href:'/' },
				{ name:'Obmann',            href:'/moderator' },
				{ name:app.district.short,  href:'/moderator/'+data.district.id },
				{ name:'Zuechter',          href:'/moderator/'+data.district.id+'/breeder' },
				{ name:`${data.breeder.firstname.charAt(0)}.${data.breeder.lastname.charAt(0)}`, href:'/moderator/'+data.district.id+'/breeder/'+data.breeder.id },
				{ name:'Stamm',             href:page.url.pathname },
			];
			app.menu.options = []

		}
	})

	console.log('Pairs page');

</script>

{#if data.district && data.breeder && data.pairs}
	<h3 class='text-center italic'>
		Stämme {data.pairs.length}
	</h3>
	<ul>
		{#each data.pairs as pair}
			<li><a href={`/moderator/${data.district.id}/breeder/${data.breeder.id}/pair/${pair.id}`}>{pair.name}</a></li>
		{/each}
	</ul>
{/if}