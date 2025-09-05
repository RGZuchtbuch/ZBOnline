<script>
    import '../app.css'; // need this once on highest level
    
    import {goto} from '$app/navigation';
    import { page } from '$app/state';
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
    
    let expired = setInterval( () => {
        const now = Date.now() / 1000; // ms to s
        if( ctx.user !== null && now - ctx.user.exp > -10 ) { // 10 s before expire
            console.log( 'Threw user out, expired')
            model.User.logout();
            goto( '/user' );
        }
    }, 5000 )

    async function loadFederation() {
        dirty.federation = false; // first, to avoid retrigger
        ctx.federation = await model.Federation.load();
    }
    
    async function loadStandard() {
        dirty.standard = false;
        ctx.standard = await model.Standard.load();
    }
    
    async function loadUser() {
        ctx.user = await model.User.load();
    }
    
    let dialog_test = null; // for dialog element
    $effect( () => {
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
        <div>Willkommen im RGZuchtbuch</div>
        <button onclick={ () => ctx.dialog=null }>OK</button>
    </div>
</dialog>

{#if ctx.federation !== null && ctx.standard !== null }
    <div class='screen-scroll-y content'><!-- fade does not work here -->
       {@render children()}
    </div>
{/if}

<style>

    .content {
        /*@apply  bg-gradient-to-br from-violet-500 to-fuchsia-500 text-black flex flex-col;*/
        @apply  text-black flex flex-col;
    }
</style>
