<script>
    import '../app.css'; // need this once on highest level

    import { onMount } from 'svelte';
    import { fade, fly, slide } from 'svelte/transition';
    import { ctx, dirty } from '$lib/js/store.svelte.js'

    import Header from '$lib/cmp/Header.svelte';
    import Menu from '$lib/cmp/Menu.svelte';
    import Title from '$lib/cmp/Title.svelte';
    import model from '$lib/js/model.js';

    let { children } = $props();

    loadUser(); // only once, the rest is handled by login/out

    ctx.crumbs = [];

    $effect( () => {
        if( dirty.federation ) loadFederation();
    });
    $effect( () => {
        if( dirty.standard ) loadStandard();
    });

    // async function load() {
    //     const response = await Promise.all([
    //         model.Federation.load(),
    //         model.Standard.load(),
    //         model.User.load() // gets it from sessionStorage
    //     ]);
    //     ctx.federation = response[0];
    //     ctx.standard = response[1];
    //     ctx.user = response[2];
    // }

    async function loadFederation() {
        console.log( 'load F' );
        dirty.federation = false; // first, to avoid retrigger
        ctx.federation = await model.Federation.load();
    }

    async function loadStandard() {
        console.log( 'load S' );
        dirty.standard = false;
        ctx.standard = await model.Standard.load();
    }

    async function loadUser() {
        console.log( 'load U' );
        ctx.user = await model.User.load();
        //dirty.user = false;
    }

    // $inspect( 'Header', ctx.header );
    // $inspect( 'Articles', ctx.articles );
    // $inspect( 'Article', ctx.article );
    // $inspect( 'Federation', ctx.federation );
    // $inspect( 'Standard', ctx.standard );
    //
    $inspect( 'Dirty', dirty.articles );


</script>


<Header />

<Menu />
<Title />


{#if ctx.federation !== null && ctx.standard !== null }

    <div class='screen-scroll-y content' in:fade={{duration:1500}}>
       {@render children()}
    </div>

    {#if ctx.dialog==='test'}
        <dialog class='absolute w-full h-full bg-[#8888]'>Test</dialog>
    {/if}

{/if}
<style>
    .content {
        @apply  border border-teal-400 bg-white text-black flex flex-col;
    }
</style>