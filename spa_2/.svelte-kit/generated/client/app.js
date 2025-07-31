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
	() => import('./nodes/43')
];

export const server_loads = [];

export const dictionary = {
		"/": [8],
		"/admin": [9,[2]],
		"/admin/district": [10,[2]],
		"/admin/log": [11,[2]],
		"/admin/setting": [12,[2]],
		"/article": [13],
		"/article/[article]": [14],
		"/breeder": [15],
		"/breeder/grader": [16],
		"/breeder/lineageform": [17],
		"/breeder/me/[breeder]": [18,[3]],
		"/breeder/me/[breeder]/pair": [19,[3]],
		"/breeder/me/[breeder]/pair/[pair]": [20,[3]],
		"/breeder/me/[breeder]/profile": [21,[3]],
		"/federation": [22],
		"/message": [23],
		"/moderator": [24,[4]],
		"/moderator/[district]": [25,[4,5]],
		"/moderator/[district]/breeder": [26,[4,5]],
		"/moderator/[district]/breeder/[breeder]": [27,[4,5,6]],
		"/moderator/[district]/breeder/[breeder]/pair": [28,[4,5,6]],
		"/moderator/[district]/breeder/[breeder]/pair/[pair]": [29,[4,5,6]],
		"/moderator/[district]/breeder/[breeder]/profile": [30,[4,5,6]],
		"/moderator/[district]/report": [31,[4,5]],
		"/moderator/[district]/result": [32,[4,5]],
		"/moderator/[district]/result/edit": [33,[4,5]],
		"/pair": [34],
		"/pair/[pair]": [35],
		"/report": [36],
		"/result": [37],
		"/result/map/year/[year]/type/[type]": [38],
		"/result/table/district/[districtId]/year/[year]": [39],
		"/result/trend/district/[districtId]/type/[type]": [40],
		"/standard": [41],
		"/test": [42,[7]],
		"/user": [43]
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