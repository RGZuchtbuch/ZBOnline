<script>
    import { onMount } from 'svelte';

    export let year;
    export let label;
    export let earlier = 250;
    export let ahead = 1;
    export let short = false;
    export let focus = false;

    let classname = '';
    export { classname as class }
    const now = new Date().getFullYear();

    let inputElement;
    let value = year; // internal year
    let error;

    const on = {
        focus: () => focus = true,
        blur: () => focus = false,
    }

    function check( value ) {
        value = Number( value );
        const now = new Date().getFullYear();
        if( value >= 0 && value <= now+1 ) {
            if( value < 100 ) {
                const centuries = Math.floor( now / 100 );
                if( value + centuries * 100 > now+ahead ) {
                    value += (centuries-ahead) * 100;
                } else {
                    value += centuries * 100;
                }
                error = null;
                year = value;
                return value;
            } else if( value >= now-earlier && value <= new Date().getFullYear()+ahead ) {
                error = null;
                year = value;
                return value;
            }
        }
        error = 'Invalid year';
        return null;
    }

    function hasFocus() {
        return document.activeElement=inputElement;
    }



    $: check(value);

    onMount( () => {
        if( focus ) inputElement.focus();
    })
</script>
{classname}
<div class='main'>
    <div class='header'>
        <div class='label'>{label}</div>
        <div class='info' title={(now-earlier)+'-'+(now+ahead)}>&#8505</div>
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
    div.info {
        font-size: 0.4rem;
        line-height: 0.4rem;
        font-weight: 400;
        color: #0F3;
        vertical-align: top;
        cursor: none;
    }

    input {
        display: block;
        border: none;
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
