<script>
    import '../app.css'; // need this once on highest level

    import {goto} from '$app/navigation';
    import { fade, fly, slide } from 'svelte/transition';
    import { ctx, dirty } from '$lib/js/store.svelte.js'

    import Header from '$lib/cmp/Header.svelte';
    import Menu from '$lib/cmp/Menu.svelte';
    import Title from '$lib/cmp/Title.svelte';
    import model from '$lib/js/model.js';

    let { children } = $props();

    loadUser(); // only once, the rest is handled by login/out

    $effect( () => {
        if( dirty.federation ) loadFederation();
    });
    $effect( () => {
        if( dirty.standard ) loadStandard();
    });

    console.log('SetInterval');
    let expired = setInterval( () => {
        //clearInterval( expired ); // in case one already runs
        const now = Date.now() / 1000; // ms to s
        //if( ctx.user ) console.log( this, 'Check', now, ctx.user.exp, ( now - ctx.user.exp )/ 1, ' mins left');
        if( ctx.user !== null && now - ctx.user.exp > -10 ) { // 10 s before expire
            console.log( 'Threw user out, expired')
            model.User.logout();
            goto( '/user' );
        } else {
            //console.log( "Tiick" );
        }
    }, 5000 )

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
//    $inspect( 'Dirty', dirty.articles );

    let dialog_test = null;

    $effect( () => {
        console.log( 'test', ctx.dialog )
        if( ctx.dialog === 'test' && dialog_test ) {
            dialog_test.showModal()
        } else {
            dialog_test.close();
        }

    });


</script>

<!--div class='bg-header text-header text-center'>Test</div-->
<Header />
<Menu />
<Title />

<dialog bind:this={ dialog_test }>
    <div class='w-128 h-64 flex flex-col gap-y-4 justify-center items-center border-header bg-header text-header'>
        <div>Wilkommen im RGZuchtbuch</div>
        <button onclick={ () => ctx.dialog=null }>OK</button>
    </div>
</dialog>

{#if ctx.federation !== null && ctx.standard !== null }
    <div class='pl-4 pt-4 screen-scroll-y content' in:fade={{duration:1500}}>
       {@render children()}
    </div>
{/if}

<style>
    .content {
        @apply  border border-teal-400 bg-white text-black flex flex-col;
    }
</style>