
// this file is generated — do not edit it


declare module "svelte/elements" {
	export interface HTMLAttributes<T> {
		'data-sveltekit-keepfocus'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-noscroll'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-preload-code'?:
			| true
			| ''
			| 'eager'
			| 'viewport'
			| 'hover'
			| 'tap'
			| 'off'
			| undefined
			| null;
		'data-sveltekit-preload-data'?: true | '' | 'hover' | 'tap' | 'off' | undefined | null;
		'data-sveltekit-reload'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-replacestate'?: true | '' | 'off' | undefined | null;
	}
}

export {};


declare module "$app/types" {
	export interface AppTypes {
		RouteId(): "/" | "/admin" | "/admin/article" | "/admin/article/[article]" | "/admin/district" | "/admin/log" | "/admin/setting" | "/admin/standard" | "/article" | "/article/[article]" | "/breeder" | "/breeder/pair" | "/breeder/pair/[pair]" | "/breeder/profile" | "/calculator" | "/calculator/grader" | "/calculator/lineage" | "/federation" | "/message" | "/moderator" | "/moderator/[district]" | "/moderator/[district]/breeder" | "/moderator/[district]/breeder/[breeder]" | "/moderator/[district]/breeder/[breeder]/pair" | "/moderator/[district]/breeder/[breeder]/pair/[pair]" | "/moderator/[district]/breeder/[breeder]/profile" | "/moderator/[district]/report" | "/moderator/[district]/result" | "/moderator/[district]/result/edit" | "/report" | "/standard" | "/user";
		RouteParams(): {
			"/admin/article/[article]": { article: string };
			"/article/[article]": { article: string };
			"/breeder/pair/[pair]": { pair: string };
			"/moderator/[district]": { district: string };
			"/moderator/[district]/breeder": { district: string };
			"/moderator/[district]/breeder/[breeder]": { district: string; breeder: string };
			"/moderator/[district]/breeder/[breeder]/pair": { district: string; breeder: string };
			"/moderator/[district]/breeder/[breeder]/pair/[pair]": { district: string; breeder: string; pair: string };
			"/moderator/[district]/breeder/[breeder]/profile": { district: string; breeder: string };
			"/moderator/[district]/report": { district: string };
			"/moderator/[district]/result": { district: string };
			"/moderator/[district]/result/edit": { district: string }
		};
		LayoutParams(): {
			"/": { article?: string; pair?: string; district?: string; breeder?: string };
			"/admin": { article?: string };
			"/admin/article": { article?: string };
			"/admin/article/[article]": { article: string };
			"/admin/district": Record<string, never>;
			"/admin/log": Record<string, never>;
			"/admin/setting": Record<string, never>;
			"/admin/standard": Record<string, never>;
			"/article": { article?: string };
			"/article/[article]": { article: string };
			"/breeder": { pair?: string };
			"/breeder/pair": { pair?: string };
			"/breeder/pair/[pair]": { pair: string };
			"/breeder/profile": Record<string, never>;
			"/calculator": Record<string, never>;
			"/calculator/grader": Record<string, never>;
			"/calculator/lineage": Record<string, never>;
			"/federation": Record<string, never>;
			"/message": Record<string, never>;
			"/moderator": { district?: string; breeder?: string; pair?: string };
			"/moderator/[district]": { district: string; breeder?: string; pair?: string };
			"/moderator/[district]/breeder": { district: string; breeder?: string; pair?: string };
			"/moderator/[district]/breeder/[breeder]": { district: string; breeder: string; pair?: string };
			"/moderator/[district]/breeder/[breeder]/pair": { district: string; breeder: string; pair?: string };
			"/moderator/[district]/breeder/[breeder]/pair/[pair]": { district: string; breeder: string; pair: string };
			"/moderator/[district]/breeder/[breeder]/profile": { district: string; breeder: string };
			"/moderator/[district]/report": { district: string };
			"/moderator/[district]/result": { district: string };
			"/moderator/[district]/result/edit": { district: string };
			"/report": Record<string, never>;
			"/standard": Record<string, never>;
			"/user": Record<string, never>
		};
		Pathname(): "/" | "/admin" | "/admin/article" | `/admin/article/${string}` & {} | "/admin/district" | "/admin/log" | "/admin/setting" | "/admin/standard" | "/article" | `/article/${string}` & {} | "/breeder" | "/breeder/pair" | `/breeder/pair/${string}` & {} | "/breeder/profile" | "/calculator" | "/calculator/grader" | "/calculator/lineage" | "/federation" | "/message" | "/moderator" | `/moderator/${string}` & {} | `/moderator/${string}/breeder` & {} | `/moderator/${string}/breeder/${string}` & {} | `/moderator/${string}/breeder/${string}/pair` & {} | `/moderator/${string}/breeder/${string}/pair/${string}` & {} | `/moderator/${string}/breeder/${string}/profile` & {} | `/moderator/${string}/report` & {} | `/moderator/${string}/result` & {} | `/moderator/${string}/result/edit` & {} | "/report" | "/standard" | "/user";
		ResolvedPathname(): `${"" | `/${string}`}${ReturnType<AppTypes['Pathname']>}`;
		Asset(): "/assets/bdrg_logo_r.png" | "/assets/bdrg_zuchtbuch.jpg" | "/assets/bdrg_zuchtbuch.png" | "/assets/icons/user-profile.svg" | "/assets/ZB_ALBS.png" | "/favicon.png";
	}
}