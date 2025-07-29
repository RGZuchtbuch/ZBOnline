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
	() => import('./nodes/45'),
	() => import('./nodes/46'),
	() => import('./nodes/47')
];

export const server_loads = [];

export const dictionary = {
		"/": [9],
		"/admin": [10,[2]],
		"/admin/district": [11,[2]],
		"/admin/log": [12,[2]],
		"/admin/setting": [13,[2]],
		"/article": [14],
		"/article/[article]": [15],
		"/breeder": [16],
		"/breeder/grader": [17],
		"/breeder/lineageform": [18],
		"/breeder/me/[breeder]": [19,[3]],
		"/breeder/me/[breeder]/pair": [20,[3]],
		"/breeder/me/[breeder]/pair/[pair]": [21,[3]],
		"/breeder/me/[breeder]/profile": [22,[3]],
		"/breeder/[breeder]": [23,[4]],
		"/breeder/[breeder]/pair": [24,[4]],
		"/breeder/[breeder]/pair/[pair]": [25,[4]],
		"/breeder/[breeder]/profile": [26,[4]],
		"/federation": [27],
		"/message": [28],
		"/moderator": [29,[5]],
		"/moderator/[district]": [30,[5,6]],
		"/moderator/[district]/breeder": [31,[5,6]],
		"/moderator/[district]/breeder/[breeder]": [32,[5,6,7]],
		"/moderator/[district]/breeder/[breeder]/pair": [33,[5,6,7]],
		"/moderator/[district]/breeder/[breeder]/pair/[pair]": [34,[5,6,7]],
		"/moderator/[district]/breeder/[breeder]/profile": [35,[5,6,7]],
		"/moderator/[district]/result": [36,[5,6]],
		"/moderator/[district]/result/edit": [37,[5,6]],
		"/pair": [38],
		"/pair/[pair]": [39],
		"/report": [40],
		"/result": [41],
		"/result/map/year/[year]/type/[type]": [42],
		"/result/table/district/[districtId]/year/[year]": [43],
		"/result/trend/district/[districtId]/type/[type]": [44],
		"/standard": [45],
		"/test": [46,[8]],
		"/user": [47]
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