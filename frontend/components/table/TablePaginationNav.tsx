import React from 'react';

import { useTableContext } from '@/contexts/TableContext';
import { LuChevronLeft, LuChevronRight, LuChevronsLeft, LuChevronsRight } from 'react-icons/lu';

export function TablePaginationNav<T>() {
    const { pagination, setFilters } = useTableContext<T>();

    const handleChangePage = (page: number) => setFilters((prev) => ({ ...prev, page: page }));

    const baseClassName = 'px-3 py-1 text-md rounded-md border border-gray-200 cursor-pointer';
    const hoverClassName = 'hover:text-violet-700 hover:bg-violet-100 hover:border-violet-700';
    const disabledClassName =
        'disabled:opacity-40 disabled:hover:text-current disabled:hover:bg-transparent disabled:hover:border-transparent';

    return (
        <div className="flex gap-2">
            {/* First Page */}
            <button
                onClick={() => handleChangePage(1)}
                disabled={pagination.meta.current_page === 1}
                className={`${baseClassName} ${hoverClassName} ${disabledClassName}`}
            >
                <LuChevronsLeft />
            </button>

            {/* Previous Page */}
            <button
                onClick={() => handleChangePage(pagination.meta.current_page - 1)}
                disabled={pagination.meta.current_page === 1}
                className={`${baseClassName} ${hoverClassName} ${disabledClassName}`}
            >
                <LuChevronLeft />
            </button>

            {/* Next Page */}
            <button
                onClick={() => handleChangePage(pagination.meta.current_page + 1)}
                disabled={pagination.meta.current_page === pagination.meta.last_page}
                className={`${baseClassName} ${hoverClassName} ${disabledClassName}`}
            >
                <LuChevronRight />
            </button>

            {/* Last Page */}
            <button
                onClick={() => handleChangePage(pagination.meta.last_page)}
                disabled={pagination.meta.current_page === pagination.meta.last_page}
                className={`${baseClassName} ${hoverClassName} ${disabledClassName}`}
            >
                <LuChevronsRight />
            </button>
        </div>
    );
}
