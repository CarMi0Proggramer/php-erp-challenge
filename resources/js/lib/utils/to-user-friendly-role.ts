import { Role } from '@/lib/enums/role';

export function toUserFriendlyRole(role: Role) {
    const userFriendlyRoles: Record<Role, string> = {
        [Role.ADMIN]: 'Admin',
        [Role.SELLER]: 'Vendedor',
        [Role.OPERATOR]: 'Operador',
        [Role.ACCOUNTANT]: 'Contador',
    };

    return userFriendlyRoles[role];
}
