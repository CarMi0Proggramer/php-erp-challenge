import type { ProductSize } from '@/lib/enums/product-size';
import type { StockMovementType } from '@/lib/enums/stock-movement-type';
import type { User } from './auth';

export type Product = {
    id: string;
    name: string;
    sku: string;
    description?: string;
    price: number;
    stock: number;
    sizes?: ProductSize[];
    images?: ProductImage[];
    primary_image?: ProductImage;
    stock_movements?: StockMovement[];
    created_at: string;
    updated_at: string;
};

export type ProductImage = {
    id: string;
    product_id: string;
    url: string;
    path: string;
    is_primary: boolean;
};

export type StockMovement = {
    id: string;
    user_id?: string;
    product_id: string;
    quantity?: number;
    stock_before: number;
    stock_balance: number;
    type: StockMovementType;
    reason?: string;
    created_at: string;
    updated_at: string;
    user?: User;
};
