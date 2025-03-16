<script>
	import { getContext } from 'svelte';
	import { page } from '$app/state';
	import { fade, fly, slide } from 'svelte/transition';
	import { goto } from '$app/navigation';
	import { navigating } from '$app/state';
	import api from '$lib/js/api.js';
	import store, { article, articles, user } from '$lib/js/store.svelte.js';
	import Form, { CheckBox, NumberInput, Status, TextArea, TextInput, validator } from '$lib/cmp/form/Form.svelte';

//	let { article } = $props();
	//let article = $state( data.article );
	let edit = $state( false );
	let remove = $state( false );
	//let app = getContext( 'state' );


	const validate = {
		level : v => validator(v).number().range( 1, 999 ).orNull().isValid(),
		author: v => validator(v).string().length( 3, 64 ).orNull().isValid(),
		title : v => validator(v).string().length( 3, 96 ).orNullIf(remove).isValid(),
		html  : v => validator(v).string().length( 3, 99999 ).isValid(),
	}

	let authorized = $derived( $user && $user.admin ); // can edit

	//$effect( () => load( +page.params.articleId ) );

	load( +page.params.articleId )

	async function load ( articleId ) {
		console.log( 'Aid', articleId );
		if( articleId === 0 ) {
			console.log( 'New', articleId )
			const newArticle = { id:0, title:'Todo', author:$user.firstname, html:'Todo' }
			store.article.update( () => newArticle );
		} else {
			console.log( 'Existing', articleId )
			const response = await api.article.get(articleId);
			if (response && response.article) {
				store.article.update(() => response.article);
				return true;
			}
			return false;
		}
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
		} else if( ! $article.titel && remove && confirm('Lösschen?') ){ // name is null and delete
			console.log( 'Sure',  );
			if( $article.id > 0 ) {
				let del = await api.article.delete( $article.id );
				if( del ) {
					console.log('Delete')
				}
				//	$article.id = null;
				//goto( navigating.from );
				//}
				//console.log( $articles )
				//articles.update( list => list.filter( a => a.id !== $article.id ) ); // all but deleted
				//console.log( $articles )
			}
			//goto( navigating.from );
			goto( '/article' );
		}
	}

	function updateArticles() {
		console.log( 'Update articles' );
		articles.update( ( list ) => list.map( a => a.id === $article.id ? $article : a ))
	}



</script>

{#if $article}

	<h2 class='header'>
		<span class='grow'>{$article.title}</span>
	</h2>
	<div class='flex flex-row items-center justify-end gap-x-2 py-2'>
		<span class='meta'>{$article.author}, {$article.modified}</span>
		{#if authorized }
			<CheckBox label='Ändern' error='' bind:value={edit} />
		{/if}
	</div>

	{#if authorized && edit}
		<Form autosubmit onsubmit={onSubmit} submitafter=2500>
			<div></div>
			<div class='flex flex-row gap-x-4 justify-between'>
				<NumberInput class='w-16' label='Folge' bind:value={$article.level} validator={ validate.level } />
				<TextInput class='grow' label='Titel' bind:value={$article.title} validator={ validate.title }/>
				<CheckBox label='Löschen' title={$article.id>1?'Nur wenn alles leer!':'Hauptartikel nicht Löschbar'} bind:value={ remove } disabled={$article.id===1 || $article.title}/>
				<Status />
			</div>

			<div class='flex flex-row'>
				<TextInput class='w-96' label='Autor' bind:value={$article.author} validator={ validate.author } />
			</div>

			<TextArea class='h-64' label='Beitrag' bind:value={$article.html} validator={ validate.html }/>
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
