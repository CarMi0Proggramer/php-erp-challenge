import type { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import { Role } from './enums/role';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function toUserFriendlyRole(role: Role) {
    const userFriendlyRoles: Record<Role, string> = {
        [Role.ADMIN]: 'Admin',
        [Role.SELLER]: 'Vendedor',
        [Role.OPERATOR]: 'Operador',
        [Role.ACCOUNTANT]: 'Contador',
    };

    return userFriendlyRoles[role];
}

export function debounce(func: (...args: any[]) => void, wait: number) {
    let timeoutId: number | null = null;

    return (...args: any[]) => {
        if (timeoutId) {
            clearTimeout(timeoutId);
        }

        timeoutId = setTimeout(() => func(...args), wait);
    };
}
