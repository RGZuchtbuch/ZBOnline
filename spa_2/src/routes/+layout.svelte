<script>
    import '../app.css'; // need this once on highest level

    import { onMount } from 'svelte';
    import { fade, fly, slide } from 'svelte/transition';
    import { ctx, store } from '$lib/js/store.svelte.js'

    import Header from '$lib/cmp/Header.svelte';
    import Menu from '$lib/cmp/Menu.svelte';
    import Title from '$lib/cmp/Title.svelte';
    import model from '$lib/js/model.js';

    let { children, data } = $props();

    // user handled in model/user.js : ctx.user = data.user;
    ctx.standard = data.standard;
    ctx.federation = data.federation;


</script>


<Header />
<Menu />
<Title />

    <div class='screen-scroll-y content' in:fade={{duration:1500}}>
       {@render children()}
    </div>

<style>
    .content {
        @apply  border border-teal-400 bg-white text-black flex flex-col;
    }
</style>