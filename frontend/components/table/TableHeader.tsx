import React from 'react';

import { useTableContext } from '@/contexts/TableContext';
import type { AllowedChecksSort } from '@/types/monitor.type';
import type { RowData, Table } from '@tanstack/react-table';
import { flexRender } from '@tanstack/react-table';
import { LuArrowUpDown } from 'react-icons/lu';

type TableHeaderProps = {
    table: Table<RowData>;
};

export function TableHeader<T>({ table }: TableHeaderProps) {
    const { setFilters } = useTableContext<T>();

    const handleSort = (key: string) =>
        setFilters((prev) => {
            const sortBy = prev.sort === key ? `-${key}` : key;
            return { ...prev, sort: sortBy as AllowedChecksSort };
        });

    return (
        <thead>
            {table.getHeaderGroups().map((headerGroup) => (
                <tr key={headerGroup.id} className="text-sm text-gray-400">
                    {headerGroup.headers.map((header) => (
                        <th
                            key={header.id}
                            onClick={() => handleSort(header.id)}
                            className="group py-3 text-lg text-black cursor-pointer select-none px-4"
                        >
                            <div className="flex items-center gap-2">
                                {flexRender(header.column.columnDef.header, header.getContext())}
                                <LuArrowUpDown
                                    size={25}
                                    className="text-gray-400 p-1 rounded-md transition group-hover:text-violet-700 group-hover:bg-violet-100"
                                />
                            </div>
                        </th>
                    ))}
                </tr>
            ))}
        </thead>
    );
}
