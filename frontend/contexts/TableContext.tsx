import type { ReactNode } from 'react';
import React, { createContext, useContext } from 'react';

import type { Pagination } from '@/types/api.type';

type TableContextType<T> = {
    pagination: Pagination<unknown>;
    filters: T;
    setFilters: React.Dispatch<React.SetStateAction<T>>;
};

type TableProviderProps<T> = {
    value: TableContextType<T>;
    children: ReactNode;
};

export const TableContext = createContext<TableContextType<unknown> | undefined>(undefined);

export function useTableContext<T>() {
    const context = useContext(TableContext) as TableContextType<T> | undefined;
    if (!context) {
        throw new Error('useTableContext must be used within a TableContextProvider');
    }
    return context;
}

export function TableProvider<T>({ value, children }: TableProviderProps<T>) {
    return (
        <TableContext.Provider value={value as TableContextType<unknown>}>
            {children}
        </TableContext.Provider>
    );
}
