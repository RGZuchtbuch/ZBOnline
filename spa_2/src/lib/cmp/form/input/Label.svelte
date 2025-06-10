<script>
    import { getContext,  onDestroy, onMount } from 'svelte';

    //let { autocomplete=null, class:classname='', disabled=false, element=$bindable(), error='!!!', label=null, maxlength=null, name=null, oninput=null, placeholder=null, title=null, type='text', validator=null, value=$bindable() } = $props();
    let { children, class:classname='', label=null, title=null } = $props();

    const form = getContext( 'form'); //form state

</script>

<div class='wrapper {classname}'>
    {#if label}
        <label class='label' for='number'>&nbsp;</label>
    {/if}
    <span {title} >{@render children()}</span>
    <label class='error' for='number'> </label>
</div>

<style>

    span {
        text-align: left;
        padding: 0 0.5em;
    }

</style>