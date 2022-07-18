<script>
    import { onMount } from 'svelte';

    export let value;
    export let label;
    export let earlier = 250;
    export let ahead = 1;
    export let short = false;
    export let focus = false;

    let classname = '';
    export { classname as class }
    const now = new Date().getFullYear();

    let inputElement;
    let year = value; // internal year
    let error;

    const on = {
        focus: () => focus = true,
        blur: () => focus = false,
    }

    function check( year ) {
        year = Number( year );
        const now = new Date().getFullYear();
        if( year >= 0 && year <= now+ahead ) {
            if( year < 100 ) { // short
                const centuries = Math.floor( now / 100 );
                if( year + centuries * 100 > now+ahead ) {
                    year += (centuries-ahead) * 100;
                } else {
                    year += centuries * 100;
                }
                error = null;
            } else if( value >= now-earlier && value <= new Date().getFullYear()+ahead ) {
                error = null;
            }
            value = year;
        } else {
            error = 'Invalid year';
        }
    }

    function hasFocus() {
        return document.activeElement=inputElement;
    }

    $: check(value);

    onMount( () => {
        if( focus ) inputElement.focus();
    })
</script>

<div class='main'>
    <div class='header'>
        <div class='label' title={(now-earlier)+'-'+(now+ahead)}>{label}</div>
    </div>
    <input id='value' type='text' style:width={short?'2em':'3em'} bind:value={value} class:error bind:this={inputElement} on:focus={on.focus} on:blur={on.blur} >
    {#if error && ! focus }<span class='error'>{error}</span>{/if}
</div>



<style>
    div.main {
        display: inline-flex;
        border: 1px solid rgb(228 228 228);
        border-bottom: 1px solid rgb(124 124 124);
        flex-direction: column;
        align-items: flex-start;
    }
    div.header {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        width: 100%;
    }

    div.label {
        font-size: 0.7rem;
        line-height: 0.7rem;
        font-weight: 400;
        color: rgb(128 128 128);
    }

    input {
        border-bottom: 1px solid rgb(0 255 0);
        padding: 0.2rem;
        text-align: right;
    }

    span.error {
        font-size: 0.7rem;
        line-height: 0.7rem;
        font-weight: 400;
        color: #ff3e00;
    }





    .error {
        border-bottom: 1px solid rgb(255 0 0);
        color: #ff3e00;
    }
</style>
