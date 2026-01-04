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
                    {row.getVisibleCells().map((cell, idx) => (
                        <td
                            key={cell.id}
                            className="w-1/3 py-4 px-4 text-black transition-colors group-hover:bg-violet-100/25 relative"
                        >
                            {/* Left gradient on first cell */}
                            {idx === 0 && (
                                <div className="absolute inset-y-0 left-0 w-15 bg-gradient-to-r from-white via-white/0 to-transparent pointer-events-none" />
                            )}

                            {/* Right gradient on last cell */}
                            {idx === row.getVisibleCells().length - 1 && (
                                <div className="absolute inset-y-0 right-0 w-15 bg-gradient-to-l from-white via-white/0 to-transparent pointer-events-none" />
                            )}
                            {flexRender(cell.column.columnDef.cell, cell.getContext())}
                        </td>
                    ))}
                </tr>
            ))}
        </tbody>
    );
}
