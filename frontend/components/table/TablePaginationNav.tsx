import React from 'react';

import { useTableContext } from '@/contexts/TableContext';
import { LuChevronLeft, LuChevronRight, LuChevronsLeft, LuChevronsRight } from 'react-icons/lu';

export function TablePaginationNav<T>() {
    const { pagination, setFilters } = useTableContext<T>();

    const handleChangePage = (page: number) => setFilters((prev) => ({ ...prev, page: page }));

    return (
        <div className="flex gap-2">
            {/* First Page */}
            <button
                onClick={() => handleChangePage(1)}
                disabled={pagination.meta.current_page === 1}
                className="px-3 py-1 text-sm rounded-md border hover:text-violet-700 hover:bg-violet-100 cursor-pointer disabled:opacity-40"
            >
                <LuChevronsLeft />
            </button>

            {/* Previous Page */}
            <button
                onClick={() => handleChangePage(pagination.meta.current_page - 1)}
                disabled={pagination.meta.current_page === 1}
                className="px-3 py-1 text-sm rounded-md border hover:text-violet-700 hover:bg-violet-100 cursor-pointer disabled:opacity-40"
            >
                <LuChevronLeft />
            </button>

            {/* Next Page */}
            <button
                onClick={() => handleChangePage(pagination.meta.current_page + 1)}
                disabled={pagination.meta.current_page === pagination.meta.last_page}
                className="px-3 py-1 text-sm rounded-md border hover:text-violet-700 hover:bg-violet-100 cursor-pointer disabled:opacity-40"
            >
                <LuChevronRight />
            </button>

            {/* Last Page */}
            <button
                onClick={() => handleChangePage(pagination.meta.last_page)}
                disabled={pagination.meta.current_page === pagination.meta.last_page}
                className="px-3 py-1 text-sm rounded-md border hover:text-violet-700 hover:bg-violet-100 cursor-pointer disabled:opacity-40"
            >
                <LuChevronsRight />
            </button>
        </div>
    );
}
