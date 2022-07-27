<script>
    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

    export let report;
    export let disabled;

    function addParent() {
        console.log( 'Add Parent' );
        report.parents = [...report.parents, { sex:'0.1', ring:'D 22 AZ 999', score:null }];
    }

    function removeParent( index ) {
        return () => {
            report.parents.splice(index, 1);
            report.parents = report.parents;
        }
    }

    function getComposition( parents ) {
        let sires = 0;
        let dames = 0;
        for( const parent of parents ) {
            if( parent.sex === '1.0' ) { //sire
                sires++;
            } else {
                dames++;
            }
        }
        return ''+sires+"."+dames;
    }
</script>

<div class='flex flex-col my-2'>
    <div>Abstammung</div>
    <div class='flex flex-row> gap-x-1'>
        <InputText class='w-12' label='Stamm' value={getComposition( report.parents )} disabled />
        <InputDate class='w-24' label='Anpaarung' bind:value={report.paired} {disabled}/>
    </div>

    <div class='flex flex-col gap-y-1'>
        {#each report.parents as parent, i}
            <div class='flex flex-row gap-x-1'>
                <InputText class='w-8' label={i===0 ? '#' : null} value={i} readonly />
                <Select class='w-16' label={i===0 ? 'Sex' : null} bind:value={parent.sex} {disabled} required>
                    {#each ['1.0', '0.1'] as sex }
                        <option value={sex}>{sex}</option>
                    {/each}
                </Select>
                <InputRing class='w-32' label={i===0 ? 'Ring [D J Bs Nr]' : null} value={parent.ring} {disabled} require/>
                <InputNumber class='w-16' label={i===0 ? 'ø Note' : null} bind:value={parent.score} min=90 max=97 step=0.1 {disabled} />

                <div class='grow'>
                    <InputText class='grow' label={i===0 ? 'Stamm Leistungen' : null} value='Todo 160 49, 90% 80%, 94.1'/>
                </div>
                <input type='button' on:click={removeParent(i)} value='X'>
            </div>
        {/each}
        <div class='rounded border bg-gray-500 text-center text-white cursor-pointer' on:click={addParent}>Elterntier zufügen</div>
    </div>

</div>

<style>

</style>