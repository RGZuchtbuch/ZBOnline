<script context='module'>
    import Item from "./accordeon/AccordionItem.svelte";

    export const AccordionItem = Item;

</script>
<script>
    import { setContext } from 'svelte';
    import { writable } from 'svelte/store';

    let id=0;

    const accordion = writable({ id:0, selected:null });
    console.log( 'Set', $accordion);

    setContext( 'accordion', accordion);
</script>

<slot/>

<style>
</style>