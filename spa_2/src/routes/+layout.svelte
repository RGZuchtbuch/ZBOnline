<script>
    import '../app.css'; // need this once on highest level


    import { onMount } from 'svelte';
    import { fade } from 'svelte/transition';

    import {cfg, ctx, dirty} from '$lib/js/store.svelte.js'
    import model from '$lib/js/model.js';

    import Header from '$lib/cmp/Header.svelte';
    import Menu from '$lib/cmp/Menu.svelte';
    import Title from '$lib/cmp/Title.svelte';

    let { children } = $props();

    let mounted = $state( false ); // to trigger transition

    onMount( async () => {
        // model.User.load().then( data => ctx.user = data );
        // model.Federation.load().then( data => ctx.federation = data );
        // model.Standard.load().then( data => ctx.standard = data );
        Promise.all( [
            model.User.load(),
            model.Federation.load(),
            model.Standard.load()
        ] ).then( values => {
            ctx.user       = values[0];
            ctx.federation = values[1];
            ctx.standard   = values[2];
            mounted = true;
        })
    });

    function setHeader() {
        ctx.title = 'Willkommen im Rassegeflügel-Zuchtbuch';
        ctx.menu = [];
        ctx.submenu = [];
        ctx.crumbs = [
            //{name: 'Gast', href: '/'}
        ];
    }
</script>



<!--div class='bg-header text-header text-center'>Test</div-->

<!-- cannot have section wrapper here due to scrolling -->
<Header />
<Menu />
{#if ctx.federation && ctx.standard}
    <Title />
    <div class='screen-scroll-y content'>
        {@render children()}
    </div>
{/if}

<style>
    .content {
        /*@apply  bg-gradient-to-br from-violet-500 to-fuchsia-500 text-black flex flex-col;*/
        @apply  text-black flex flex-col;
    }
</style>
