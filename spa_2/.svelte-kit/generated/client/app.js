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
		"/": [8],
		"/admin": [9,[2]],
		"/admin/district": [10,[2]],
		"/admin/log": [11,[2]],
		"/admin/setting": [12,[2]],
		"/admin/standard": [13,[2]],
		"/article": [14],
		"/article/[article]": [15],
		"/breeder": [16,[3]],
		"/breeder/pair": [17,[3]],
		"/breeder/pair/[pair]": [18,[3]],
		"/breeder/profile": [19,[3]],
		"/calculator": [20],
		"/calculator/grader": [21],
		"/calculator/lineage": [22],
		"/federation": [23],
		"/message": [24],
		"/moderator": [25,[4]],
		"/moderator/[district]": [26,[4,5]],
		"/moderator/[district]/breeder": [27,[4,5]],
		"/moderator/[district]/breeder/[breeder]": [28,[4,5,6]],
		"/moderator/[district]/breeder/[breeder]/pair": [29,[4,5,6]],
		"/moderator/[district]/breeder/[breeder]/pair/[pair]": [30,[4,5,6]],
		"/moderator/[district]/breeder/[breeder]/profile": [31,[4,5,6]],
		"/moderator/[district]/report": [32,[4,5]],
		"/moderator/[district]/result": [33,[4,5]],
		"/moderator/[district]/result/edit": [34,[4,5]],
		"/report": [35],
		"/result": [36],
		"/result/map/year/[year]/type/[type]": [37],
		"/result/table/district/[districtId]/year/[year]": [38],
		"/result/trend/district/[districtId]/type/[type]": [39],
		"/standard": [40],
		"/test": [41,[7]],
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