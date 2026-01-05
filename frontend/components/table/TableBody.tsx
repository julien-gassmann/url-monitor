import React from 'react';

import type { RowData, Table } from '@tanstack/react-table';
import { flexRender } from '@tanstack/react-table';

type TableBodyProps = {
    table: Table<RowData>;
};

export function TableBody({ table }: TableBodyProps) {
    return (
        <tbody>
            {table.getRowModel().rows.map((row) => (
                <tr
                    key={row.id}
                    className="group relative border-t border-gray-200 transition-colors"
                >
                    {row.getVisibleCells().map((cell, idx) => {
                        const isFirst = idx === 0;
                        const isLast = idx === row.getVisibleCells().length - 1;

                        return (
                            <td
                                key={cell.id}
                                className="relative py-4 px-4 text-black transition-colors group-hover:bg-violet-100/25"
                            >
                                {isFirst && (
                                    <div className="absolute inset-y-0 left-0 w-8 bg-gradient-to-r from-white to-transparent pointer-events-none" />
                                )}
                                {isLast && (
                                    <div className="absolute inset-y-0 right-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none" />
                                )}

                                <div className="relative z-10">
                                    {flexRender(cell.column.columnDef.cell, cell.getContext())}
                                </div>
                            </td>
                        );
                    })}
                </tr>
            ))}
        </tbody>
    );
}
