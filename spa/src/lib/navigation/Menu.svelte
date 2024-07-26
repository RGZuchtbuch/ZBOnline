<script>
	import {meta} from 'tinro';
	import {onMount, setContext} from 'svelte';
	import {slide} from 'svelte/transition';
	import screens from '../../js/screens.js';
	import Item from './Item.svelte';
	import api from '../../js/api.js';
	import { admin, moderator, user } from '../../js/store.js';

	let route = meta();

	let focus = null;
	let articles = [];

	let showMenu = false;

	// writables
	const adminDistrict = admin.district;
	const adminBreeder = admin.breeder;
	const moderatorDistrict = moderator.district;
	const moderatorBreeder = moderator.breeder;

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

	console.log( 'Admin', $adminDistrict, $adminBreeder );
</script>

<header class='relative flex flex-row justify-center'>
	<a class='absolute top-0.25 left-1 z-50' href='/'>
		<img class='w-6 print:w-24 md:w-16 md:mt-2' src='../assets/bdrg_logo_r.png' alt='BDRG Logo'>
	</a>

	<div class='text-center font-bold italic no-print'>
		Das Rassegeflügel Zuchtbuch {#if $user} für {$user.name} {/if}
	</div>

	<div class='sm:hidden'>smallest</div>
	<div class='hidden sm:block md:hidden'>small</div>
	<div class='hidden md:block'>medium</div>

	<button type='button' class='absolute top-0 right-1 w-6 border-0 md:hidden text-xl no-print' on:click={toggleMenu}>
		{#if showMenu}&#10006;{:else}&#8803;{/if}
	</button>
</header>

<nav class:showMenu class='hidden md:flex flex-col md:flex-row bg-header rounded justify-between gap-x-1 mb-1 md:mb-8 px-2 print:hidden' on:click={onSelected} transition:slide>
	<div class='hidden md:block w-16'></div>
	<div class='flex flex-col md:flex-row'>
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
				{#if $moderatorDistrict}
					<div>Verband {$moderatorDistrict.short}</div>
					<a href={'/obmann/verband/'+$moderatorDistrict.id+'/leistung'}>&#9755; Leistungen</a>
					<a href={'/obmann/verband/'+$moderatorDistrict.id+'/zuechter'}>&#9755; Züchter</a>
					<a href={'/obmann/verband/'+$moderatorDistrict.id}>&#9755; Verbandsdaten</a>
					{#if $moderatorBreeder}
						<div>Züchter {$moderatorBreeder.lastname}</div>
						<a href={'/obmann/verband/'+$moderatorDistrict.id+'/zuechter/'+$moderatorBreeder.id}>&#9755; Mitglied</a>
						<a href={'/obmann/verband/'+$moderatorDistrict.id+'/zuechter/'+$moderatorBreeder.id+'/meldung'}>&#9755; Meldungen</a>
					{/if}
				{/if}
			</Item>
		{/if}

		{#if $user && $user.admin }
			<Item name='Admin' bind:focus={focus}>
				<a href='/admin/verband'>&#9755; Verbände</a>
				{#if $adminDistrict }
					<div>Verband {$adminDistrict.short}</div>
					<a href={'/admin/verband/'+$adminDistrict.id+'/leistung'}>&#9755; Leistungen</a>
					<a href={'/admin/verband/'+$adminDistrict.id+'/zuechter'}>&#9755; Züchter</a>
					<a href={'/admin/verband/'+$adminDistrict.id }>&#9755; Verbandsdaten</a>
					{#if $adminBreeder}
						<div>Züchter {$adminBreeder.lastname}</div>
						<a href={'/admin/verband/'+$adminDistrict.id+'/zuechter/'+$adminBreeder.id}>&#9755; Mitglied</a>
						<a href={'/admin/verband/'+$adminDistrict.id+'/zuechter/'+$adminBreeder.id+'/meldung'}>&#9755; Meldungen</a>
					{/if}
				{/if}
			</Item>
		{/if}

	</div>

	<div class='hidden w-16 md:flex justify-center text-white'>
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