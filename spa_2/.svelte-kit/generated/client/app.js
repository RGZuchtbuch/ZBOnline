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
	() => import('./nodes/42')
];

export const server_loads = [];

export const dictionary = {
		"/": [15],
		"/admin": [16,[2]],
		"/admin/article": [17,[2,3]],
		"/article": [18,[4]],
		"/article/[articleId]": [19,[4,5]],
		"/breeder/[breederId]": [20,[6]],
		"/breeder/[breederId]/pair": [21,[6,7]],
		"/district": [22,[8]],
		"/moderator": [23],
		"/moderator/[districtId]": [24,[9]],
		"/moderator/[districtId]/breeder": [25,[9]],
		"/moderator/[districtId]/breeder/[breederId]": [26,[9,10]],
		"/moderator/[districtId]/breeder/[breederId]/pair": [27,[9,10]],
		"/moderator/[districtId]/breeder/[breederId]/pair/[pairId]": [28,[9,10]],
		"/moderator/[districtId]/breeder/[breederId]/profile": [29,[9,10]],
		"/moderator/[districtId]/result": [30,[9]],
		"/pair/[pairId]": [31,[11]],
		"/report": [32,[12]],
		"/report/map/year/[year]/type/[type]": [33,[12]],
		"/report/table/district/[districtId]/year/[year]": [34,[12]],
		"/report/trend/district/[districtId]/type/[type]": [35,[12]],
		"/result": [36,[13]],
		"/result/map/year/[year]/type/[type]": [37,[13]],
		"/result/table/district/[districtId]/year/[year]": [38,[13]],
		"/result/trend/district/[districtId]/type/[type]": [39,[13]],
		"/standard": [40],
		"/test": [41,[14]],
		"/user": [42]
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