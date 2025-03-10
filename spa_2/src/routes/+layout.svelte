<script>
    import '../app.css'; // need this once on highest level
    import { page } from '$app/state';
    import store, { federation, standard, menu, title, url } from '$lib/js/store.svelte.js';
    import { load } from './load.js';

    import {onMount, setContext} from 'svelte';
    import { fade, fly, slide } from 'svelte/transition';

    import Header from '$lib/cmp/Header.svelte';
    import Menu from '$lib/cmp/Menu.svelte';
    import Title from '$lib/cmp/Title.svelte';

    //await load(); // updates store federation and standard

    url.update( () => page.url );

    let { children, data } = $props(); // get page
    //let app = $state( { title:'RGZuchtbuch Online 2.0', menu:{ trail:[], options:[] }, changed:false } );
    //setContext( 'state', app )

    onMount( async () => {
        await load(); // loads fed and standard
    })

    // note: user is in store

    /**
    * note, this layout has header, menu and children
    * these are set through app.title, app.menu.trail (breadcrumbs) and app.menu.options ( current submenu )
    **/
</script>

<Header />
<Menu />
<Title />

{#if $federation && $standard }
    <div class='screen-scroll-y content' in:fade={{duration:1000}} >
        {@render children()}
    </div>
{/if}



<style>
    .content {
        @apply  border border-teal-400 bg-white text-black flex flex-col;
    }
</style>