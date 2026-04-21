import type { ProductSize } from '@/lib/enums/product-size';

export type Product = {
    id: string;
    name: string;
    sku: string;
    description?: string;
    price: number;
    stock: number;
    sizes?: ProductSize[];
    images?: ProductImage[];
};

export type ProductImage = {
    id: string;
    product_id: string;
    url: string;
    is_primary: boolean;
};
