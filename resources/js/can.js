import { usePage } from '@inertiajs/vue3';

export function can(permission) {
    const { dashboard } = usePage().props;
    const user = dashboard.user;

    if (!user) return false;

    // Allow access if user has the 'super_admin' role
    if (user.super_admin) {
        return true;
    }

    // Check for the specific permission
    return user.permissions.some(userPermission => userPermission.name === permission);
}
