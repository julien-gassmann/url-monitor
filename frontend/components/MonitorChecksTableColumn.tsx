import type { ColumnDef } from '@tanstack/react-table';

import { DateCell, HttpCodeCell, StatusCell } from '@/components/MonitorChecksTableCells';

export const column = {
    date<T>(key: keyof T, label: string): ColumnDef<T> {
        return {
            accessorKey: key as string,
            header: label,
            cell: ({ getValue }) => <DateCell value={String(getValue())} />,
        };
    },

    status<T>(key: keyof T, label: string): ColumnDef<T> {
        return {
            accessorKey: key as string,
            header: label,
            cell: ({ getValue }) => <StatusCell value={String(getValue())} />,
        };
    },

    httpCode<T>(key: keyof T, label: string): ColumnDef<T> {
        return {
            accessorKey: key as string,
            header: label,
            cell: ({ getValue }) => <HttpCodeCell value={String(getValue())} />,
        };
    },
};
