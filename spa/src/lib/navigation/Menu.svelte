<script>
	import {meta} from 'tinro';
	import {onMount, setContext} from 'svelte';
	import {slide} from 'svelte/transition';
	import screens from '../../js/screens.js';
	import Item from './Item.svelte';
	import api from '../../js/api.js';
	import {breeder, district, user} from '../../js/store.js';

	let focus = null;

	let articles = [];

	let route = meta();

	let showMenu = false;

	function toggleMenu() {
		showMenu = ! showMenu;
	}

	function hideOnChange() {
		console.log( 'Change', route.url );
		showMenu = false;
	}

	function loadArticles() {
		api.article.getAll().then( response => {
			articles = response.articles;
		})
	}

	function onSelected( event ) {
		if( event.target.tagName === 'A' ) {
			showMenu = screen.width >= screens.md; // only true on small screen
			focus = null;
			console.log( 'Selected', screen.width, screens.md, showMenu );
		}
	}

	onMount( () => {
		loadArticles();
	})

	$: hideOnChange( route );
</script>

<header class='relative flex flex-row justify-center'>
	<a class='absolute top-0.25 left-1 z-50' href='/'>
		<img class='w-6 print:w-24 md:w-16 md:mt-2' src='../assets/bdrg_logo_r.png' alt='BDRG Logo'>
	</a>

	<div class='text-center font-bold italic no-print'>
		Das Rassegeflügel Zuchtbuch {#if $user} für {$user.name} {/if} {showMenu}
	</div>
	<button type='button' class='absolute top-0 right-1 w-6 border-0 md:hidden text-2xl no-print' on:click={toggleMenu}>
		{#if showMenu}&#10006;{:else}&#8803;{/if}
	</button>
</header>

<nav class:showMenu class='hidden md:flex flex-col md:flex-row bg-header rounded justify-center gap-x-1 my-0 md:mb-8 print:hidden' on:click={onSelected} transition:slide>
	<Item name='Info' bind:focus={focus}>
		{#each articles as article}
			<a href={'/zuchtbuch/'+article.id}>&#9755; {article.title}</a>
		{/each}
	</Item>
	<Item name='Verbände' bind:focus={focus}>
		<a href={'/verband'}>&#9755; Verbände im Zuchtbuch</a>
	</Item>
	<Item name='Rassestandard' bind:focus={focus}>
		<a href={'/standard'}>&#9755; BDRG Rassestandard</a>
	</Item>
	<Item name='Leistungen' bind:focus={focus}>
		<a href={ '/leistungen' }>&#9755; Leistungen</a>
		<a class='hidden md:block' href={ '/leistungen/nachweis' }>&#9755; Abstammungsnachweis Formular</a>
		<a href={ '/leistungen/rechner' }>&#9755; Zuchtbuch Bewertungsrechner</a>
	</Item>
	{#if $user && $user.moderator.length > 0 }
		<Item name='Obmann' bind:focus={focus}>
			<a href='/obmann/verband'>&#9755; Verbände</a>
			{#if $district}
				<div>Verband {$district.short}</div>
				<a href={'/obmann/verband/'+$district.id+'/leistung'}>&#9755; Leistungen</a>
				<a href={'/obmann/verband/'+$district.id+'/zuechter'}>&#9755; Züchter</a>
				{#if $breeder}
					<div>Züchter {$breeder.lastname}</div>
					<a href={'/obmann/verband/'+$district.id+'/zuechter/'+$breeder.id}>&#9755; Mitglied</a>
					<a href={'/obmann/verband/'+$district.id+'/zuechter/'+$breeder.id+'/meldung'}>&#9755; Meldungen</a>
				{/if}
			{/if}


		</Item>
	{/if}

	<div class='flex justify-center text-white'>
		{#if $user}
			<a href='/abmelden'>Abmelden</a>
		{:else}
			<a href='/anmelden'>Anmelden</a>
		{/if}
	</div>
</nav>


<style>
	.showMenu {
		@apply flex print:hidden;
	}
</style>