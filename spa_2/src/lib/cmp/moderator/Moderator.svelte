<script>
	import { page } from '$app/state';
	import { ctx } from '$lib/js/store.svelte.js';
	import { fullName } from '$lib/js/tools.js';
	import Article from '$lib/cmp/article/Article.svelte';
	import {onMount} from 'svelte';
	import model from '$lib/js/model.js';

	let { district } = $props();

	let manual = $state( null );

	onMount( () => {
		Promise.all( [
			model.Article.load( 13 )
		]).then( ( values ) => {
			manual = values[0];
		});
	});
</script>


{#if ctx.user }

	<h3 class='text-center'>Hallo {ctx.user.firstname}, hier kannst du, als Obmann, was schaffen. ;)</h3>

	<div class='mx-16 my-8'>
		<h3>Wähle dein Verband zum verwalten</h3>
		<ul>
			{#each ctx.districts as district }
				<li><a href={`/moderator/district/${district.id}`}>{district.name}</a></li>
			{/each}
		</ul>
	</div>
	<hr>
	<Article article={manual}/>

{/if}


<style>
    img {
        @apply border border-teal-400 rounded p-4;
    }
</style>