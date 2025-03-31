<script>

	import { page } from '$app/state';
	import { fade, fly, slide } from 'svelte/transition';
	import { navigating } from '$app/state';
	import {goto, invalidate, invalidateAll} from '$app/navigation';
	import store from '$lib/js/store.svelte.js';
	import Form, { CheckBox, NumberInput, Status, TextArea, TextInput, validator } from '$lib/cmp/form/Form.svelte';
	import { Article } from '$lib/js/article.svelte.js';

	let { article=$bindable() } = $props();
	let edit = $state( false );
	let remove = $state( false );


	const validate = {
		level : v => validator(v).number().range( 1, 999 ).orNull().isValid(),
		author: v => validator(v).string().length( 3, 64 ).orNull().isValid(),
		title : v => validator(v).string().length( 3, 96 ).orNullIf(remove).isValid(),
		html  : v => validator(v).string().length( 3, 99999 ).isValid(),
	}

	let authorized = $derived( store.user && store.user.admin ); // can edit



	async function onSubmit( event ) {
		console.log( 'Submit article' );
		if( article.title ) {
			return Article.save( article );
		} else if( ! article.titel && remove && confirm('Lösschen?') ){ // name is null and delete
			Article.delete( article.id );
			goto( '/article' ); // back to list, no return
		}
	}

</script>

{#if article}

	<!--h2 class='header'>
		<span class='grow'>{article.title}</span>
	</h2-->
	<div class='flex flex-row items-center justify-end gap-x-2 py-2'>
		<span class='meta'>{article.author}, {article.modified}</span>
		{#if authorized }
			<CheckBox label='Ändern' error='' bind:value={edit} />
		{/if}
	</div>

	{#if authorized && edit}
		<Form class='p-4' autosubmit onsubmit={onSubmit} submitafter=2500>
			<div></div>
			<div class='flex flex-row gap-x-4 justify-between'>
				<NumberInput class='w-16' label='Folge' bind:value={article.level} validator={ validate.level } />
				<TextInput class='grow' label='Titel' bind:value={article.title} validator={ validate.title }/>
				<CheckBox bind:value={ remove }
					label='Löschen'
				    title={article.id>1?'Nur wenn alles leer!':'Hauptartikel nicht Löschbar'}
					disabled={article.id===1 || article.title}
				/>
				<Status />
			</div>

			<div class='flex flex-row'>
				<TextInput class='w-96' label='Autor' bind:value={article.author} validator={ validate.author } />
			</div>

			<TextArea class='h-64' label='Beitrag' bind:value={article.html} validator={ validate.html }/>
		</Form>
	{/if}


	<p class='px-6 py-2'>
		{@html article.html}
	</p>

	<div class='footer'>
		<a href='/article'>← Zurück zu den Beiträgen</a>
	</div>
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
