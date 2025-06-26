<script>
    import '../app.css'; // need this once on highest level

    import { onMount } from 'svelte';
    import { fade, fly, slide } from 'svelte/transition';
    import { ctx } from '$lib/js/store.svelte.js'

    import Header from '$lib/cmp/Header.svelte';
    import Menu from '$lib/cmp/Menu.svelte';
    import Title from '$lib/cmp/Title.svelte';
    import {Federation, Standard, User} from '$lib/js/model.js';

    let { children, data } = $props();


    onMount( async () => {
        const response = await Promise.all( [
            Federation.load(),
            Standard.load(),
            User.load()
        ] );

        ctx.federation = response[0];
        ctx.standard = response[1];
        ctx.user = response[2];
    });

    $inspect( 'F',  ctx.standard );

</script>


<Header />
<Menu />
<Title />

{#if ctx.federation && ctx.standard }
    <div class='screen-scroll-y content' in:fade={{duration:1500}}>
       {@render children()}
    </div>
{/if}

<style>
    .content {
        @apply  border border-teal-400 bg-white text-black flex flex-col;
    }
</style>