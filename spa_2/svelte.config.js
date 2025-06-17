import adapter from '@sveltejs/adapter-static';
import { vitePreprocess } from '@sveltejs/vite-plugin-svelte'; // tailwind


/** @type {import('@sveltejs/kit').Config} */
const config = {
	compilerOptions: {
		runes: true, // Which could also be false if you want to force the Svelte 4 compiler/syntax

	},

	kit: {
		adapter: adapter({
			fallback: 'index.html',
		}),
	},

	preprocess: vitePreprocess(), // tailwind
};



export default config;
