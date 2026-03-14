<script>
	import {onDestroy, onMount} from 'svelte';
	import { goto, invalidate, invalidateAll} from '$app/navigation';
	import { ctx, dirty } from '$lib/js/store.svelte.js';
	import model from '$lib/js/model.js';

	import Form, { CheckBox, NumberInput, Status, TextArea, TextInput, validator } from '$lib/cmp/form/Form.svelte';

	let { article=$bindable() } = $props();

	let edit = $state( article && article.id === 0 );
	let remove = $state( false );
	let authorized = $derived( ctx.user && ctx.user.admin ); // can edit

	$effect( () => {
		if( edit ) {
			Wysi({
				el: '#wysi',
				autoGrow: true,
				tools: [
					'format', '|',
					'bold', 'italic', 'underline', '|',
					{ label: 'Text alignment', items: ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify'] }, '|',
					'ul', 'ol', '|',
					'indent', 'outdent', '|',
					'link', 'image', 'hr', '|',
					'removeFormat'
				],
				onChange: ( content ) => {
					article.html = content;
				},
			});
		}
	})

	const validate = {
		level : v => validator(v).number().range( 1, 999 ).orNull().isValid(),
		author: v => validator(v).string().length( 3, 64 ).orNull().isValid(),
		title : v => validator(v).string().length( 3, 96 ).orNullIf(remove).isValid(),
		html  : v => validator(v).string().length( 3, 99999 ).isValid(),
	}

	async function onSubmit( event ) {
		console.log( 'Submit article' );
		//article.html = document.querySelector('#wysi').value;
		if( article.title ) {
			//changed = true;
			let ok = await model.Article.save( article );
			dirty.articles++; // use ++ to not have to set back to false, we only want to detect change for reloading in articles/+page $effect
			return ok;
		} else if( ! article.titel && remove && confirm('Lösschen?') ){ // name is null and delete
			//changed = true;
			let response = await model.Article.delete( article.id );
			await goto('/admin/article'); // back to list, no return
			//return response
		}
	}


</script>

<section>
	{#if article}
		<div class='flex flex-row items-center justify-end gap-x-2 p-2'>
			<span class='meta'>{article.author}, {article.modified}</span>
			{#if authorized }
				<span class='print:hidden'>
					<CheckBox label='Ändern' error='' bind:value={edit} />
				</span>
			{/if}
		</div>

		{#if authorized && edit}
			<Form class='flex flex-col p-4 px-14' autosubmit onsubmit={onSubmit} submitafter=2500>
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

				<!--TextArea class='h-64' label='Beitrag' bind:value={article.html} validator={ validate.html }/-->

				<textarea id='wysi' class='h-16 bg-white' bind:value={article.html} ></textarea>
			</Form>
		{/if}



		{#if ! edit}
			<p class='px-4 md:px-16  py-2'>
				{@html article.html}
			</p>
		{/if}
	{/if}
</section>


<style>
	.meta {
		font-size: 0.6em;
		@apply italic;
	}
</style>
