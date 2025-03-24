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
	() => import('./nodes/38')
];

export const server_loads = [];

export const dictionary = {
		"/": [11],
		"/admin": [12,[2]],
		"/admin/article": [13,[2,3]],
		"/article": [14],
		"/article/[articleId]": [15],
		"/breeder/[breederId]": [16,[4]],
		"/breeder/[breederId]/pair": [17,[4,5]],
		"/district": [18],
		"/moderator": [19],
		"/moderator/[districtId]": [20,[6]],
		"/moderator/[districtId]/breeder": [21,[6]],
		"/moderator/[districtId]/breeder/[breederId]": [22,[6,7]],
		"/moderator/[districtId]/breeder/[breederId]/pair": [23,[6,7]],
		"/moderator/[districtId]/breeder/[breederId]/pair/[pairId]": [24,[6,7]],
		"/moderator/[districtId]/breeder/[breederId]/profile": [25,[6,7]],
		"/moderator/[districtId]/result": [26,[6]],
		"/pair/[pairId]": [27,[8]],
		"/report": [28],
		"/report/map/year/[year]/type/[type]": [29],
		"/report/table/district/[districtId]/year/[year]": [30],
		"/report/trend/district/[districtId]/type/[type]": [31],
		"/result": [32,[9]],
		"/result/map/year/[year]/type/[type]": [33,[9]],
		"/result/table/district/[districtId]/year/[year]": [34,[9]],
		"/result/trend/district/[districtId]/type/[type]": [35,[9]],
		"/standard": [36],
		"/test": [37,[10]],
		"/user": [38]
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