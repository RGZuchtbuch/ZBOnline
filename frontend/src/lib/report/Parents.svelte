<script>
    import InputButton from '../input/Button.svelte';
    import InputDate from '../input/Date.svelte';
    import InputNumber from '../input/Number.svelte';
    import InputRing from '../input/Ring.svelte';
    import InputText from '../input/Text.svelte';
    import Select from '../select/Select.svelte';

    export let paired = null;
    export let parents = [];
    export let disabled = false;

    function addParent() {
        console.log( 'Add Parent' );
        parents = [...parents, { index:Date.now(), sex:'0.1', ring:'D 22 AZ 999', score:null, parentsId:null }]; // index just for rendering
    }

    function removeParent( index ) {
        return () => {
            parents.splice(index, 1);
            parents = parents;
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

    console.log( 'Parents', parents );
</script>

<div class='flex flex-col my-2'>
    <div>Abstammung</div>

    <div class='flex flex-col gap-y-1'>
        <InputDate class='w-24' label={'Angepaart am'} bind:value={paired} {disabled} />

        {#each parents as parent, i (parent.index)}
            <div class='flex flex-row gap-x-1'>
                <div class='grow flex flex-row gap-x-1'>
                    <InputNumber class='w-8' label={i===0 ? '#' : null} value={i} readonly />
                    <Select class='w-16' label={i===0 ? 'Sex' : null} bind:value={parent.sex} title='Hahn (1.0) oder Henne (0.1)' {disabled} required>
                        {#each ['1.0', '0.1'] as sex }
                            <option value={sex}>{sex}</option>
                        {/each}
                    </Select>
                    <InputRing class='w-32' label={i===0 ? 'Ring [D J Bs Nr]' : null} bind:value={parent.ring} {disabled} required/>
                    <InputNumber class='w-16' label={i===0 ? '∅ Note' : null} bind:value={parent.score} min=90 max=97 step=0.1 {disabled} />
                </div>
                <div class='flex flex-row gap-x-1'>
                    <InputText class='grow' label={i===0 ? 'Stamm Leistungen' : null} value='Todo 160 49, 90% 80%, 94.1' readonly/>
                </div>
                <InputButton class='w-8' on:click={removeParent(i)} label={i===0 ? 'Entf' : null} value='X' />
            </div>
        {/each}
        <div class='rounded border bg-gray-500 text-center text-white cursor-pointer' on:click={addParent}>Elterntier zufügen</div>
    </div>

</div>

<style>

</style>