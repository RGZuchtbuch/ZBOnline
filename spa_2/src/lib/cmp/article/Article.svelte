<script>
	import { getContext } from 'svelte';
	import { page } from '$app/state';
	import { fade, fly, slide } from 'svelte/transition';
	import { goto } from '$app/navigation';
	import { navigating } from '$app/state';
	import api from '$lib/js/api.js';
	import store, { article, articles, user } from '$lib/js/store.svelte.js';
	import Form, { CheckBox, Status, TextArea, TextInput, validator } from '$lib/cmp/form/Form.svelte';

//	let { article } = $props();
	//let article = $state( data.article );
	let edit = $state( false );
	let remove = $state( false );
	let app = getContext( 'state' );


	const validate = {
		author: v => validator(v).string().length( 3, 64 ).orNull().isValid(),
		title : v => validator(v).string().length( 3, 96 ).isValid(),
		html  : v => validator(v).string().length( 3, 99999 ).isValid(),
	}

	let authorized = $derived( $user && $user.admin );
	//app.changed = false;
	console.log( 'User', store.user );

	async function load ( articleId ) {
		const response = await api.article.get( articleId );
		if( response && response.article ) {
			store.article.update( () => response.article );
			return true;
		}
		return false;
	}

	async function onSubmit( event ) {
		console.log( 'Submit article' );
		if( $article.title ) {
			if( $article.id > 0 ) { // existing pair
				console.log('Put' );
				let response = await api.article.put( $article.id, $article );
				if( response ) {
					updateArticles();
					return true;
				}
				console.error( 'Put problem... should not happen')
				return false;
			} else { // new pair
				console.log( 'Post' );
				let response = await api.article.post( $article );
				if( response && response.id ) {
					$article.id = response.id; // take newly created id from server
					updateArticles();
					return true;
				} else {
					console.error( 'Post problem... should not happen')
					return false;
				}
			}
		} else if( ! $article.titel && remove ){ // name is null and delete
			if( $article.id > 0 ) {
				//let del = await api.article.delete( pair.id );
				//if( del ) {
					console.log( 'Delete' )
				//	$article.id = null;
				//goto( navigating.from );
				//}
				articles.update( list => list.filter( a => a.id !== $article.id ) ); // all but deleted
			}
			goto( navigating.from );
		}
	}

	function updateArticles() {
		console.log( 'Update articles' );
		articles.update( ( list ) => list.map( a => a.id === $article.id ? $article : a ))
	}

	load( page.params.articleId );

</script>

{#if $article}

	<h2 class='header'>
		<span class='grow'>{$article.title}</span>
	</h2>
	<div class='flex flex-row items-center justify-end gap-x-2 py-2'>
		<span class='meta'>{$article.author}, {$article.modified} {remove}</span>
		{#if authorized }
			<CheckBox label='Ändern' error='' bind:value={edit} />
		{/if}
	</div>

	{#if authorized && edit}
		<Form autosubmit onsubmit={onSubmit} submitafter=2500>
			<div><Status /></div>
			<div class='flex flex-row gap-x-4 justify-between'>
				<TextInput class='grow' label='title' bind:value={$article.title} validator={ validate.title }/>
				<CheckBox label='Löschen' title={$article.id>1?'Nur wenn alles leer!':'Hauptartikel nicht Löschbar'} bind:value={ remove } disabled={$article.id===1 || $article.title}/>
			</div>

			<div class='flex flex-row'>
				<TextInput class='w-96' label='Autor' bind:value={$article.author} validator={ validate.author } />
			</div>

			<TextArea class='h-64' label='title' bind:value={$article.html} validator={ validate.html }/>
		</Form>
	{/if}


	<p>
		{@html $article.html}
	</p>

{/if}

<style>
	.header {
		@apply flex flex-row px-4;
	}
	.meta {
		font-size: 0.6em;
		@apply italic;
	}
</style>
