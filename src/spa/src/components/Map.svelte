<script>
    export let promise;

    let districts = [{ x:-50, y:0, r:50 }, { x:50, y:-50, r:50 }, { x:50, y:100, r:75 }];

</script>
Map !!
{#await promise}
    loading...
{:then districgts}
    <div class='container'>
        <img width=480 height=640 src='/assets/de.svg' />
        <svg width=480 height=640 viewbox='-100, -100, 200, 200'>
            {#each districts as district}
                    <circle r={district.r} cx={district.x} cy={district.y} fill='transparent' stroke='#101010' stroke-width=2 />
            {/each}
        </svg>
    </div>
{:catch error}
    error
{/await}