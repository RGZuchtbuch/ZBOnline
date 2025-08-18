<script>
    import { getContext,  onDestroy, onMount } from 'svelte';


    let { class:classname='', disabled=false, element=$bindable(), error='Fehler', label=null, name=null, onchange=null, title=null, validator=null, value=$bindable() } = $props();
    const form = getContext( 'form'); // store
    let valid = $state( true );




//    $state.validators.push( validate );

    function validate() { // called from form with this context
        if( validator ) valid = validator( value ); // only if dirty or was invalid, only the active, dirty input can become invalid by design!
        return valid;
    }

   // function onchange() { console.log( 'Select Change'); }

//    function onInput( event ) {
        //dirty = true; // needs validation
//    }
    onMount( () => { // catch input and register validator
//        element.addEventListener( 'input', onInput );
        if( form && validator ) form.validators.push( validate ); // add this.validate with it's context
    });
    onDestroy( () => { // remove validator
        if( form ) {
            let index = form.validators.indexOf(validate);
            if (index >= 0) form.validators.splice(index, 1); // remove this validator;
        }
    });

</script>

<div class='{classname}'>
    {#if label}
        <label class='label' for='number'>{label}</label>
    {/if}
    <select class='input' class:valid
            {onchange}
            bind:this={element} bind:value={value}
            {disabled} {name} {title}
    >
        <slot /> <!-- options-->
    </select>
    <label class='error' class:valid for='number'>{error}</label>
</div>

<style>
    div {
        display:flex;
        flex-direction: column;
    }
</style>