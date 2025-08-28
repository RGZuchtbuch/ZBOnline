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
	() => import('./nodes/39')
];

export const server_loads = [];

export const dictionary = {
		"/": [7],
		"/admin": [8,[2]],
		"/admin/article": [9,[2]],
		"/admin/article/[article]": [10,[2]],
		"/admin/district": [11,[2]],
		"/admin/log": [12,[2]],
		"/admin/setting": [13,[2]],
		"/admin/standard": [14,[2]],
		"/article": [15],
		"/article/[article]": [16],
		"/breeder": [17,[3]],
		"/breeder/pair": [18,[3]],
		"/breeder/pair/[pair]": [19,[3]],
		"/breeder/profile": [20,[3]],
		"/federation": [21],
		"/federation/message": [22],
		"/moderator": [23,[4]],
		"/moderator/[district]": [24,[4,5]],
		"/moderator/[district]/breeder": [25,[4,5]],
		"/moderator/[district]/breeder/[breeder]": [26,[4,5,6]],
		"/moderator/[district]/breeder/[breeder]/pair": [27,[4,5,6]],
		"/moderator/[district]/breeder/[breeder]/pair/[pair]": [28,[4,5,6]],
		"/moderator/[district]/breeder/[breeder]/profile": [29,[4,5,6]],
		"/moderator/[district]/report": [30,[4,5]],
		"/moderator/[district]/result": [31,[4,5]],
		"/moderator/[district]/result/edit": [32,[4,5]],
		"/report": [33],
		"/standard": [34],
		"/tool": [35],
		"/tool/grader": [36],
		"/tool/lineage": [37],
		"/user": [38],
		"/user/reset": [39]
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