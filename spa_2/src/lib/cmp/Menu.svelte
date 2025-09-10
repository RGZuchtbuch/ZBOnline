<script>
	import { page } from '$app/state';
	import { fade, fly, slide } from 'svelte/transition';
	import { ctx } from '$lib/js/store.svelte.js';

	$effect( () => {
		let options = [
			{ name: 'Infos',        href: '/article'},
			{ name: 'Verbände',     href: '/federation'},
			{ name: 'Standard',     href: '/standard'},
			{ name: 'Leistungen',   href: '/report'},
			{ name: 'Toolbox',      href: '/tool'},
		];
		let roles = [];
		if( ctx.user ) {
			roles.push( {name: 'Züchter', href: `/breeder` } );
			if (ctx.user.moderator.length > 0) roles.push({name: 'Obmann', href: '/moderator'});
			if (ctx.user.admin) roles.push({name: 'Admin', href: '/admin'});
		}
		ctx.menu = { options:options, roles:roles }; // single trigger
	});
	// ➭

</script>

{#if ctx && ctx.title && ctx.menu }
	<div class='flex flex-col border-header bg-header text-header print:hidden' >
		<div class='grow flex flex-row justify-end gap-x-2 py-1 pr-8'>
			<nav class='flex flex-row gap-x-2'>
				{#each ctx.menu.options as option, i}
					{#if i>0} • {/if}
					{#if page.url.pathname.startsWith( option.href ) }
						<a class='underline' href={ctx.menustate[ option.href ]} title={'Zum '+option.name}>{option.name}</a>
					{:else}
						<a href={ctx.menustate[ option.href ]} title={'Zum '+option.name}>{option.name}</a>
					{/if}
				{/each}
			</nav>
			{#if ctx.menu.roles.length > 0}
				:
				<nav class='flex flex-row gap-x-2'>
					{#each ctx.menu.roles as role, i}
						{#if i>0} • {/if}
						{#if page.url.pathname.startsWith( role.href ) }
							<a class='underline font-bold' href={ctx.menustate[ role.href ]} title={'Zum '+role.name}>{role.name}</a>
						{:else}
							<a href={ctx.menustate[ role.href ]} title={'Zum '+role.name}>{role.name}</a>
						{/if}
					{/each}
				</nav>
			{/if}
		</div>
		<div class='grow flex flex-row text-sm gap-x-2'>
			<nav class='grow flex flex-row justify-end gap-x-1 italic'>
				{#each ctx.crumbs as crumb, i}
					{#key crumb.name}
						{#if i>0} / {/if}
						{#if i < ctx.crumbs.length-1}
							<a href={crumb.href} class='pr-1' title='Zurück'> {crumb.name}</a>
						{:else}
							<span class='pr-1 underline font-bold cursor-default' title='Hier bist du' in:fade={{axis:'x', duration:500}}> {crumb.name}</span>
						{/if}
					{/key}
				{/each}
			</nav>
			{#if ctx.submenu.length > 0}
				:
			{/if}
			<nav class='flex flex-row pr-20 gap-x-1'>
				{#each ctx.submenu as option, i}
					{#if i>0} • {/if}
					{#key option.name}
						{#if option.href}
							<a href={option.href} title={'Zum '+option.name}>{option.name}</a>
						{:else}
							<span title='Jetzige Wahl'>{option.name}</span><!-- not happening anymore ?-->
						{/if}
					{/key}
				{/each}
			</nav>
		</div>
	</div>
{/if}
