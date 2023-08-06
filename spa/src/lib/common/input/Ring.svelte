<script>

    export let value;
    export let label;
    export let name;
    export let disabled = false;
    export let readonly = false;
    export let required = false;
    export let error = 'z.B. D 21 AZ 999';
    export let element;

    let classname = '';
    export {classname as class}

    let input = value;
    let invalid = false;

    const patterns = {
        default: ( input ) => {
            console.log( 'Input D', input );
            const match = input.match(/^(\d\d?)[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // 21 AZ 999, defaults to D
            return match ? 'D '+match[1]+' '+match[2].toUpperCase()+' '+match[3] : null;
        },
        EU: (input) => {
            console.log( 'Input EU', input );
            const match = input.match(/^([a-zA-Z]+)[\ \.]*(\d{2})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // D 21 AZ 999
            return match ? match[1].toUpperCase()+' '+match[2]+' '+match[3].toUpperCase()+' '+match[4] : null;
        }
    };

    let on = {
        focus: () => {
        },
        blur: () => {
        },
    }

    $: clear( value ); // clear from outside
    $: validate(input); // on input changed

    function clear( value ) {
        if( value===null ) {
            input = value;
        }
    }

    function validate(input) {
        if( input ) {
            invalid = true; // unless a match
            for (let key in patterns) {
                console.log( 'Key', key );
                const ring = patterns[key](input);
                console.log( 'Ring', ring );
                if (ring) {
                    console.log( 'Found', ring );
                    value = ring;
                    invalid = false;
                    return;
                   // break; // on match
                }
            }
        } else {
            invalid = required;
        }
    }

</script>

<div class='input {classname} flex flex-col gap-0' title='Land Jahr Buchstaben Nummer : D 20 AZ 999'>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='data' class:invalid id='input' type='text' {name}
           bind:value={input} bind:this={element}
           {disabled} {readonly}
           {required}
           spellcheck=false
           on:focus={on.focus}
           on:blur={on.blur}
    >
    <span class:invalid>
        {#if invalid} {error} {:else} &nbsp; {/if}
    </span>
</div>

<style>
</style>