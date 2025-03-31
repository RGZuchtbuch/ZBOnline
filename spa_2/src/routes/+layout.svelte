<script>
    import '../app.css'; // need this once on highest level

    import { fade, fly, slide } from 'svelte/transition';
    import store from '$lib/js/store.svelte.js'

    import Header from '$lib/cmp/Header.svelte';
    import Menu from '$lib/cmp/Menu.svelte';
    import Title from '$lib/cmp/Title.svelte';

    let { children, data } = $props();

    store.federation = data.federation;
    store.standard   = data.standard;
    store.user       = data.user;

    /**
    * note, this layout has header, menu and children
    * these are set through app.title, app.menu.trail (breadcrumbs) and app.menu.options ( current submenu )
    **/
</script>

<Header />
<Menu />
<Title />

{#if data.federation && data.standard }
    <div class='screen-scroll-y content' in:fade={{duration:1000}} >
        {@render children()}
    </div>
{/if}

<style>
    .content {
        @apply  border border-teal-400 bg-white text-black flex flex-col;
    }
</style>