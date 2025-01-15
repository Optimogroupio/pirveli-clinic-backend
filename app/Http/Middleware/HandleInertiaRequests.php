<?php

namespace App\Http\Middleware;

use App\Models\DashboardUser;
use App\Models\Locale;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'ziggy' => fn() => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'locales' => Locale::get(),
            'toast' => [
                'message' => $request->session()->get('toast.message'),
                'type' => $request->session()->get('toast.type', 'info'),
                'autoDismiss' => $request->session()->get('toast.autoDismiss', 5),
                'position' => $request->session()->get('toast.position', 'bottom-right') // default position
            ],
            'dashboard.user' => fn() => auth('dashboard')->check()
                ? DashboardUser::with('roles', 'permissions')->find(auth('dashboard')->id())
                : null,
        ];
    }
}
