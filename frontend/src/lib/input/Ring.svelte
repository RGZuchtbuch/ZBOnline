<script>

    export let value;
    export let label;
    export let name;
    export let disabled = false;
    export let readonly = false;
    export let required = false;
    export let error = 'z.B. D21 AZ 999';

    let classname = '';
    export {classname as class}

    let input = value;
    let invalid = false;

    let on = {
        focus: () => {
        },
        blur: () => {
        },
    }

    $: validate(input);

    function validate(input) {
        const patterns = {
            default: ( input ) => {
                const match = input.match(/^(\d\d?)[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // 21 AZ 999, defaults to D
                return match ? { country:'D ', year:match[1], code:match[2]+' '+match[3] } : null;
            },
            EU: (input) => {
                const match = input.match(/^([a-zA-Z]+)[\ \.]*(\d{2})[\ \.]*([a-zA-Z]+)[\ \.]*(\d+)$/); // D 21 AZ 999
                return match ? { country:match[1].toUpperCase(), year:match[2], code:match[3].toUpperCase()+' '+match[4] } : null;
            }
        }

//match = input.match(/^([0-9]{2}|[0-9]{4})\-(1[0-2]|0[1-9]|[1-9])\-(3[0-1]|[12][0-9]|0[1-9]|[1-9])$/); // iso 2022-7-22

        invalid = true; // unless a match
        for (let key in patterns) {
            const ring = patterns[ key ]( input );
            if( ring ) {
                value = input;
                invalid = false;
                break;
            }
        }

    }

</script>

<div class='input {classname} flex flex-col gap-0'>
    {#if label}
        <label class='label' for='input'>{label}</label>
    {/if}
    <input class='data' class:invalid id='input' type='text' {name}
           bind:value={input}
           {disabled} {readonly}
           {required}
           spellcheck=false
           on:focus={on.focus}
           on:blur={on.blur}
    >
    <span class='invalid'>
        {#if invalid && !disabled} {error} {/if}
    </span>
</div>

<style>
</style>