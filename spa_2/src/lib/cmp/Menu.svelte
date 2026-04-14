<script>
	import { page } from '$app/state';
	import { fade, fly, slide } from 'svelte/transition';
	import { cfg, ctx } from '$lib/js/store.svelte.js';

	//let fullMenu = $derived( ! page.url ); // true.pathname === '/'
	//let fullMenu = $state( true );//page.pathName === '/';

	let wideMenu = $derived( getWideMenu( ctx.user ) ); // wide
	let narrowMenu = $derived( getNarrowMenu( ctx.user ) ); // narrow


	function getWideMenu( user ) {
		let menu = { options:null, roles:null };
		menu.options = [
			{ name: 'Infos',        href: '/article'},
			{ name: 'Verbände',     href: '/federation'},
			{ name: 'Standard',     href: '/standard'},
			{ name: 'Leistungen',   href: '/report'},
			{ name: 'Toolbox',      href: '/tool'},
		];
		menu.roles = [];
		if( user ) {
			menu.roles.push( {name: 'Züchter', href: `/breeder` } );
			if (ctx.user.moderator.length > 0) menu.roles.push({name: 'Obmann', href: '/moderator'});
			if (ctx.user.admin) menu.roles.push({name: 'Admin', href: '/admin'});
		}
		return menu;
	}

	function getNarrowMenu( user ) {
		let menu = { options:null, roles:null };
		menu.options = [
			{ name: 'An/Abmelden',     href: '/user'},
			{ name: 'Infos',        href: '/article'},
			{ name: 'Verbände',     href: '/federation'},
			{ name: 'Standard',     href: '/standard'},
			{ name: 'Leistungen',   href: '/report'},
			{ name: 'Toolbox',      href: '/tool'},
		];
		menu.roles = [];
		if( user ) {
			menu.roles.push( {name: 'Züchter', href: `/breeder` } );
			//if (ctx.user.moderator.length > 0) menu.roles.push({name: 'Obmann', href: '/moderator'});
			//if (ctx.user.admin) menu.roles.push({name: 'Admin', href: '/admin'});
		}
		return menu;
	}
	$effect( () => {
		if( page.url ) ctx.menuOpen = false; //close on page change
	})

</script>

<section>
		<!-- mobile -->
		{#if ctx.menuOpen}

			<nav class='flex flex-col md:hidden print:hidden' transition:slide={{ duration:cfg.fadeIn }}>
				{#each narrowMenu.options as option, i}
					<a class='mobile' href={option.href} title={'Zum '+option.name}>{option.name}</a>
				{/each}
				{#each narrowMenu.roles as role, i}
					<a class='mobile' href={role.href} title={'Zum '+role.name}>{role.name}</a>
				{/each}
			</nav>
		{/if}

		<!-- wide menu -->
		<div class='hidden md:flex flex-col border-header bg-header text-header print:hidden' >
			<div class='grow flex flex-row justify-end gap-x-4 py-1 pr-8'>
				<nav class='flex flex-row gap-x-1'>
					{#each wideMenu.options as option, i}
						{#if i>0} • {/if}
						{#if page.url.pathname.startsWith( option.href ) }
							<a class='underline' href={ option.href } title={'Zum '+option.name}>{option.name}</a>
							<!--a-- class='underline' href={ctx.menustate[ option.href ]} title={'Zum '+option.name}>{option.name}</a-->
						{:else}
							<a href={ctx.menustate[ option.href ]} title={'Zum '+option.name}>{option.name}</a>
						{/if}
					{/each}
				</nav>
				{#if wideMenu.roles.length > 0}
					:
					<nav class='flex flex-row gap-x-2 italic'>
						{#each wideMenu.roles as role, i}
							{#if i>0} • {/if}
							{#if page.url.pathname.startsWith( role.href ) }
								<a class='underline font-bold' href={ role.href } title={'Zum '+role.name}>{role.name}</a>
								<!--a-- class='underline font-bold' href={ctx.menustate[ role.href ]} title={'Zum '+role.name}>{role.name}</a-->
							{:else}
								<a href={ctx.menustate[ role.href ]} title={'Zum '+role.name}>{role.name}</a>
							{/if}
						{/each}
					</nav>
				{/if}
			</div>

			<!-- Crumbs and SubMenu -->
			<div class='grow flex flex-row text-sm gap-x-4'>

				<nav class='grow flex flex-row justify-end gap-x-1 italic'>
					{#each ctx.crumbs as crumb, i}
						{#key i}
							<span in:slide={{axis:'x', duration:cfg.fadeIn}}>
								{#if i>0} / {/if}
								{#if i < ctx.crumbs.length-1}
									<a href={crumb.href} class='pr-1' title='Zurück'>
										{crumb.name}
									</a>
								{:else}
									<span class='pr-1 underline font-bold cursor-default' title='Hier bist du'>
										{crumb.name}
									</span>
								{/if}
							</span>
						{/key}
					{/each}
				</nav>

				{#if ctx.submenu && ctx.submenu.length > 0}
					:
				{/if}
				<nav class='flex flex-row pr-20 gap-x-1'>
					{#each ctx.submenu as option, i}
						{#if i>0} • {/if}
						{#key option.name}
							<span in:slide={{axis:'x', duration:cfg.fadeIn}}>
								<a href={option.href} title={'Zum '+option.name}>{option.name}</a>
							</span>
						{/key}
					{/each}
				</nav>
			</div>
		</div>

</section>

<style>
	.mobile {
		@apply border rounded-none border-teal-800 bg-header text-header font-bold text-center py-2 ;
	}
</style>
