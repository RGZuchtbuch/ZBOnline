<script>
	import TextInput from './Text.svelte';

    let { autocomplete=null, class:classname='', disabled=false, element=$bindable(), error='!', label=null, name=null, placeholder=null, title=null, validator=null, value=$bindable() } = $props();
</script>


<TextInput class={classname} type='email'
    bind:element={element} bind:value={value}
    {disabled} {error} {label} {name} {placeholder} {title} {validator}
    {oninput} {onchange} {onfocus} {onblur} {autocomplete}
/>

