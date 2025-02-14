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
	() => import('./nodes/36')
];

export const server_loads = [];

export const dictionary = {
		"/": [12],
		"/article": [13],
		"/article/[articleId]": [14],
		"/breeder/[breederId]": [15,[2]],
		"/breeder/[breederId]/pair": [16,[2,3]],
		"/district": [17],
		"/moderator": [18,[4]],
		"/moderator/[districtId]": [19,[4,5]],
		"/moderator/[districtId]/breeder": [20,[4,5,6]],
		"/moderator/[districtId]/breeder/[breederId]": [21,[4,5,6,7]],
		"/moderator/[districtId]/breeder/[breederId]/pair": [22,[4,5,6,7]],
		"/moderator/[districtId]/breeder/[breederId]/pair/[pairId]": [23,[4,5,6,7,8]],
		"/moderator/[districtId]/breeder/[breederId]/profile": [24,[4,5,6,7]],
		"/moderator/[districtId]/pair": [25,[4,5]],
		"/moderator/[districtId]/pair/[pairId]": [26,[4,5,9]],
		"/moderator/[districtId]/report": [27,[4,5]],
		"/moderator/[districtId]/result": [28,[4,5]],
		"/pair/[pairId]": [29,[10]],
		"/result": [30],
		"/result/map/year/[year]/type/[type]": [31],
		"/result/table/district/[districtId]/year/[year]": [32],
		"/result/trend/district/[districtId]/type/[type]": [33],
		"/standard": [34],
		"/test": [35,[11]],
		"/user": [36]
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