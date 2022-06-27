<script>

    export let value = new Date().getFullYear();
    export let disabled = true;

    let className = ''
    export { className as class }

    let localYear = shortYear( value );

    function onInput( event ) {
        value = longYear( localYear );
    }

    function shortYear( longYear ) {
        return longYear % 100;
    }

    function longYear( shortYear ) {
        if( shortYear >= 0 && shortYear <= 99) {
            let ages = Math.floor( new Date().getFullYear() / 100 );
            console.log( 'Ages', ages );
            let year = ages*100 + shortYear;
            console.log( 'Ages', ages, year );
            return year;
        }
    }
</script>

<input class={className} type='number' min=00 max=99 step=1 maxlength='2' bind:value={localYear} on:input={onInput} {disabled}>
