<script>
    import { navigating, page } from '$app/state';
    import { fade, fly, slide } from 'svelte/transition';
	import '../app.css';
    import { app } from '$lib/js/store.svelte.js'
    import { profile_icon } from '$lib/cmp/snippets.svelte';

    let { children, data } = $props(); // get page

    app.districts = data.districts;
    app.standard = data.standard;

</script>

<div class='flex flex-row'>
    <div class='w-32'></div>
    <div class='grow font-bold text-sm text-center' in:fade>
        BDRG Zuchtbuch
    </div>
    <div class='w-32 flex flex-row text-sm justify-end'>{app.user?.name} <a href='/user'>{@render profile_icon()}</a></div>

</div>

<div class='flex flex-row border border-teal-400 bg-teal-200 print:hidden' >
    <a href='/' class='-mt-5' title='Das BDRG Zuchtbuch Logo'>
        <img src='/assets/bdrg_logo_r.png' class='h-16 p-1 mx-2' alt='BDRG Rassegeflügelzuchtbuch Logo' />
    </a>

    <div class='grow flex flex-row items-center'>
        {#each app.menu.trail as step, i}
            {#if i>0} / {/if}
            {#key step.name}
                <div class='flex flex-row'>
                    {#if i < app.menu.trail.length-1}
                        <a href={step.href} class='pr-1'>{step.name}</a>
                    {:else}
                        <a href={step.href} class='pr-1 font-bold'>{step.name}</a>
                    {/if}
                </div>
            {/key}
        {/each} :
        <div class='flex flex-row px-4 items-center gap-x-2' in:slide={{axis:'x', duration:500}}>
            {#each app.menu.options as option, i}
                {#key option.name}
                    <a href={option.href}>{option.name}</a>
                {/key}
            {/each}
        </div>
    </div>


</div>



<div class='flex flex-row justify-between items-center' >
    <a onclick={() => history.go(-1)} title='Zurück'>←</a>
    {#key app.title}
        <h1 class='text-center whitespace-nowrap' in:fade={{duration:400}}>
            {app.title}
        </h1>
    {/key}
    <a onclick={() => history.go(+1)} title='Vorwärtz'>→</a>
</div>

<div class='screen-scroll-y border border-teal-400 flex flex-col p-2'>
    {@render children()}
</div>



<style>
    @media screen { /* only scroll content on screen, not on print */
        .screen-scroll-y {
            @apply h-full min-h-0 overflow-y-scroll;
        }

    }
</style>