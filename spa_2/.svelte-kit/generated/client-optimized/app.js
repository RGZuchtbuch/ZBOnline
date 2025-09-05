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
		"/": [9],
		"/admin": [10,[2]],
		"/admin/article": [11,[2]],
		"/admin/article/[article]": [12,[2]],
		"/admin/district": [13,[2]],
		"/admin/log": [14,[2]],
		"/admin/setting": [15,[2]],
		"/admin/standard": [16,[2]],
		"/article": [17],
		"/article/[article]": [18],
		"/breeder": [19,[3]],
		"/breeder/pair": [20,[3]],
		"/breeder/pair/[pair]": [21,[3]],
		"/breeder/profile": [22,[3]],
		"/federation": [23],
		"/federation/message": [24],
		"/moderator": [25,[4]],
		"/moderator/district": [26,[4]],
		"/moderator/district/[district]": [27,[4,5]],
		"/moderator/district/[district]/breeder": [28,[4,5]],
		"/moderator/district/[district]/breeder/[breeder]": [29,[4,5,6]],
		"/moderator/district/[district]/breeder/[breeder]/pair": [30,[4,5,6]],
		"/moderator/district/[district]/breeder/[breeder]/pair/[pair]": [31,[4,5,6]],
		"/moderator/district/[district]/breeder/[breeder]/profile": [32,[4,5,6]],
		"/moderator/district/[district]/report.obs": [33,[4,5]],
		"/moderator/district/[district]/result": [34,[4,5,7]],
		"/moderator/district/[district]/result/breeder": [35,[4,5,7]],
		"/moderator/district/[district]/result/breeder/[breeder]/pair": [36,[4,5,7,8]],
		"/moderator/district/[district]/result/breeder/[breeder]/pair/[pair]": [37,[4,5,7,8]],
		"/moderator/district/[district]/result/district": [38,[4,5,7]],
		"/report": [39],
		"/standard": [40],
		"/tool": [41],
		"/tool/grader": [42],
		"/tool/lineage": [43],
		"/user": [44],
		"/user/reset": [45]
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