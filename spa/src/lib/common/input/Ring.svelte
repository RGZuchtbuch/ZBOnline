<script>

    export let value;
    export let label;
    export let name = '';
    export let disabled = false;
    export let readonly = false;
    export let required = false;
    export let error = 'z.B. D 21 AZ 999';
    export let element;
    export let invalid = false;

    let classname = '';
    export {classname as class}

    let input = value;

    const patterns = {
        default: ( input ) => {
//            console.log( 'Input D', input );
            const match = input.match(/^(\d\d?)[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // 21 AZ 999, defaults to D
            return match ? 'D '+match[1]+' '+match[2].toUpperCase()+' '+match[3] : null;
        },
        EU: (input) => {
//            console.log( 'Input EU', input );
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

    function update(value ) {
        if( value===null ) {
            input = value;
        }
    }

    function validate(input) {
        if( input ) {
            invalid = true; // unless a match

            for (let key in patterns) {
                const ring = patterns[key](input);
                if (ring) {
                    value = ring;
                    invalid = false;
                    return;
                } else {
                    value = input;
                    invalid = true;
                }
            }
        } else {
            invalid = required;
            value = input;
        }
    }

    $: update( value ); // clear from outside
    $: validate(input); // on input changed
</script>

<div class='input {classname} flex flex-col gap-0' title='Land Jahr Buchstaben Nummer : D 20 AZ 999'>
    {#if label}
        <label class='label' for='input'>{label} : {invalid}</label>
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