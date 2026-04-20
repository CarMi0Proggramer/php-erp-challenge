import type { Role } from '@/lib/enums/role';

export type User = {
    id: string;
    name: string;
    email: string;
    avatar?: string;
    created_at: string;
    updated_at: string;
    role: Role;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
