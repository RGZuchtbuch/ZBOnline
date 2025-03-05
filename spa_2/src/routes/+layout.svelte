<script>
    import '../app.css'; // need this once on highest level
    import { setContext } from 'svelte';
    import { fade, fly, slide } from 'svelte/transition';

    import Header from '$lib/cmp/Header.svelte';
    import Menu from '$lib/cmp/Menu.svelte';
    import Title from '$lib/cmp/Title.svelte';

    let { children, data } = $props(); // get page
    let temp = { title:'RGZuchtbuch Online 2.0', menu:{ trail:[], options:[] } }
    //let page       = $state( temp );
    let state = $state( temp );
    setContext( 'state', state )

    // note: user is in store

    /**
    * note, this layout has header, menu and children
    * these are set through app.title, app.menu.trail (breadcrumbs) and app.menu.options ( current submenu )
    **/
</script>

<Header />
<Menu />
<Title />

{#key state.title}
    <div class='screen-scroll-y content' in:fade={{duration:1000}} >
        {@render children()}
    </div>
{/key}



<style>
    .content {
        @apply  border border-teal-400 bg-white text-black flex flex-col;
    }
</style>