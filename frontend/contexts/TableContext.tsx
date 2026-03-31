import type { ReactNode } from 'react';
import React, { createContext, useContext } from 'react';

import type { Pagination, PaginationFilters } from '@/types/api.type';

export type TableContextType = {
    pagination: Pagination<unknown>;
    filters: PaginationFilters<unknown, unknown>;
    setFilters: React.Dispatch<React.SetStateAction<PaginationFilters<unknown, unknown>>>;
};

type TableProviderProps = {
    value: TableContextType;
    children: ReactNode;
};

export const TableContext = createContext<TableContextType | undefined>(undefined);

export function useTableContext() {
    const context = useContext(TableContext) as TableContextType | undefined;
    if (!context) {
        throw new Error('useTableContext must be used within a TableContextProvider');
    }
    return context;
}

export function TableProvider({ value, children }: TableProviderProps) {
    return <TableContext.Provider value={value}>{children}</TableContext.Provider>;
}
