<script>
	import {router} from 'tinro';

	export let href;

	let selected = false;

	function setSelected() {
		selected = $router.path.startsWith( href );
	}

	$: setSelected( router, href )
</script>


<a class:selected {href}><slot></slot></a>


<style>
	.selected {
		@apply font-bold;
	}
</style>