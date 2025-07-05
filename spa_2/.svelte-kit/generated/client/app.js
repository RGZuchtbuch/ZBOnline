export { matchers } from './matchers.js';

export const nodes = [
	() => import('./nodes/0'),
	() => import('./nodes/1'),
	() => import('./nodes/2'),
	() => import('./nodes/3'),
	() => import('./nodes/4'),
	() => import('./nodes/5'),
	() => import('./nodes/6'),
	() => import('./nodes/7'),
	() => import('./nodes/8'),
	() => import('./nodes/9'),
	() => import('./nodes/10'),
	() => import('./nodes/11'),
	() => import('./nodes/12'),
	() => import('./nodes/13'),
	() => import('./nodes/14'),
	() => import('./nodes/15'),
	() => import('./nodes/16'),
	() => import('./nodes/17'),
	() => import('./nodes/18'),
	() => import('./nodes/19'),
	() => import('./nodes/20'),
	() => import('./nodes/21'),
	() => import('./nodes/22'),
	() => import('./nodes/23'),
	() => import('./nodes/24'),
	() => import('./nodes/25'),
	() => import('./nodes/26'),
	() => import('./nodes/27'),
	() => import('./nodes/28'),
	() => import('./nodes/29'),
	() => import('./nodes/30'),
	() => import('./nodes/31'),
	() => import('./nodes/32'),
	() => import('./nodes/33'),
	() => import('./nodes/34'),
	() => import('./nodes/35'),
	() => import('./nodes/36'),
	() => import('./nodes/37'),
	() => import('./nodes/38'),
	() => import('./nodes/39'),
	() => import('./nodes/40'),
	() => import('./nodes/41'),
	() => import('./nodes/42'),
	() => import('./nodes/43'),
	() => import('./nodes/44'),
	() => import('./nodes/45')
];

export const server_loads = [];

export const dictionary = {
		"/": [21],
		"/admin": [22,[2]],
		"/admin/article": [23,[2,3]],
		"/article": [24,[4]],
		"/article/[article]": [25,[4,5]],
		"/breeder/[breederId]": [26,[6]],
		"/breeder/[breederId]/pair": [27,[6,7]],
		"/federation": [28,[8]],
		"/moderator": [29,[9]],
		"/moderator/[district]": [30,[9,10]],
		"/moderator/[district]/breeder": [31,[9,10,11]],
		"/moderator/[district]/breeder/[breeder]": [32,[9,10,11,12]],
		"/moderator/[district]/breeder/[breeder]/pair/[pair]": [33,[9,10,11,12,13]],
		"/moderator/[district]/breeder/[breeder]/profile": [34,[9,10,11,12]],
		"/moderator/[district]/result": [35,[9,10,14]],
		"/moderator/[district]/result/edit": [36,[9,10,14,15]],
		"/moderator/[district]/result/[year]/edit/section/[section]/group/[group]": [37,[9,10,14,16]],
		"/report": [38,[17]],
		"/result": [39,[18]],
		"/result/map/year/[year]/type/[type]": [40,[18]],
		"/result/table/district/[districtId]/year/[year]": [41,[18]],
		"/result/trend/district/[districtId]/type/[type]": [42,[18]],
		"/standard": [43,[19]],
		"/test": [44,[20]],
		"/user": [45]
	};

export const hooks = {
	handleError: (({ error }) => { console.error(error) }),
	
	reroute: (() => {}),
	transport: {}
};

export const decoders = Object.fromEntries(Object.entries(hooks.transport).map(([k, v]) => [k, v.decode]));

export const hash = false;

export const decode = (type, value) => decoders[type](value);

export { default as root } from '../root.js';