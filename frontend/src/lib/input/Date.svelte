<script>

    export let value;
    export let label;
    export let name;
    export let disabled = false;
    export let readonly = false;
    export let required = false;
    export let min = new Date( 1850,0,1 ); // before start of all this 1850-01-01
    export let max = new Date( new Date().getFullYear() + 1,11,31 ); // next year
    export let error = min.getFullYear()+' - '+max.getFullYear();

    let classname = '';
    export { classname as class }

    let input = null;
    let invalid = false;

    let on = {
        focus: () => {},
        blur: () => {},
    }

    $: validate( input );

    function extendYear( year ) {
        if( year >=0 && year < 100 ) {
            const maxYear = max.getFullYear() % 100;
            console.log( 'MaxYear', maxYear );
            if( year > maxYear ) {
                return max.getFullYear() - 100 - maxYear + year;
            } else {
                return max.getFullYear() - maxYear + year;
            }
        }
        return year;
    }

    function validate() {
        let match; // D
        let date = null;
        if (input !== null) {
            console.log('V', 'checking', input );
            match = input.match(/^([0-9]{2}|[0-9]{4})\-(1[0-2]|0[1-9]|[1-9])\-(3[0-1]|[12][0-9]|0[1-9]|[1-9])$/); // iso 2022-7-22
            if( match ) {
                date = new Date( extendYear( parseInt( match[1] ) ), parseInt( match[2] )-1, parseInt( match[3] )) // yyyy, mm, dd
                invalid = false;
            } else {
                match = input.match(/^(3[0-1]|[12][0-9]|0[1-9]|[1-9])[\-\.](1[0-2]|0[1-9]|[1-9])[\-\.]([0-9]{2}|[0-9]{4})$/); // iso 2022-7-22
                console.log( 'check NL/D', match);
                if (match) {
                    date = new Date( extendYear( parseInt( match[3] ) ), parseInt( match[2] )-1, parseInt( match[1] )) // yyyy, mm, dd
                    invalid = false;
                }
            }
            console.log( 'Match', match, date);
        }
        if( match && date >= min && date <= max ) {
            value = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate(); //iso, mind the month +1
            invalid = false;
        } else {
            value = null;
            invalid = required || input !== '' || input !== null;
        }
        console.log( 'V',value, date );
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
           on:focus={on.focus}
           on:blur={on.blur}
    >
        <span class='invalid'>
            &nbsp;
            {#if invalid && ! disabled}
                {error}
            {/if}
        </span>
</div>

<style>
</style>