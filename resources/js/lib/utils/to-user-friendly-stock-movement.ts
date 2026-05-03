import { StockMovementType } from '@/lib/enums/stock-movement-type';

export function toUserFriendlyStockMovement(movement: StockMovementType) {
    const userFriendlyMovements: Record<StockMovementType, string> = {
        [StockMovementType.PURCHASE]: 'Compra',
        [StockMovementType.SALE]: 'Venda',
        [StockMovementType.ADJUSTMENT]: 'Ajuste',
    };

    return userFriendlyMovements[movement];
}
