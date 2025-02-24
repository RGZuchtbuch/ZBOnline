<script>
    import { setContext } from 'svelte';
    import { fade, fly, slide } from 'svelte/transition';
	import '../app.css'; // need this once on highest level

    import Header from '$lib/cmp/Header.svelte';
    import Menu from '$lib/cmp/Menu.svelte';
    import Title from '$lib/cmp/Title.svelte';
//    import store from '$lib/js/store.svelte.js';


    let { children, data } = $props(); // get page

    let federation = $state( data.federation )
    let standard   = $state( data.standard )
    let page       = $state( { title:'RGZuchtbuch Online 2.0', menu:{ trail:[], options:[] } } );

    setContext( 'federation', federation );
    setContext( 'standard',   standard );
    setContext( 'page',       page );

    /**
    * note, this layout has header, menu and children
    * these are set through app.title, app.menu.trail (breadcrumbs) and app.menu.options ( current submenu )
    **/
</script>

<Header />
<Menu />
<Title />

{#key page.title}
    <div class='screen-scroll-y border border-teal-400 flex flex-col p-2' in:fade={{duration:1000}} >
        {@render children()}
    </div>
{/key}



<style>
</style>