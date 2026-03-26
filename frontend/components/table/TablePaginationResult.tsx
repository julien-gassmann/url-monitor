import type { ChangeEvent } from 'react';
import React from 'react';

import { useTableContext } from '@/contexts/TableContext';
import type { MetadataResponse } from '@/types/metadata.type';

type TablePaginationResultProps = {
    metadata: MetadataResponse<'paginate-checks'>;
};

export function TablePaginationResult<T>({ metadata }: TablePaginationResultProps) {
    const { pagination, filters, setFilters } = useTableContext<T>();
    const handleChangePerPage = (e: ChangeEvent<HTMLSelectElement>) =>
        setFilters((prev) => {
            const perPage = e.target.value;
            return { ...prev, per_page: perPage, page: 1 };
        });

    return (
        <div className="font-normal text-md space-y-2">
            {/* Result Per Page */}
            <div>
                Résultats :
                <span className="font-semibold mx-2">
                    {pagination.data.length
                        ? (pagination.meta.current_page - 1) * filters.per_page +
                          1 +
                          '-' +
                          ((pagination.meta.current_page - 1) * filters.per_page +
                              pagination.data.length)
                        : 0}
                </span>
                sur
                <span className="font-semibold text-md mx-2">{pagination.meta.total}</span>
            </div>

            {/* Per Page */}
            <div className="flex items-center space-x-2">
                <label>Par page :</label>
                <select
                    onChange={handleChangePerPage}
                    defaultValue={filters.per_page}
                    className="w-fit font-semibold border border-gray-200 hover:text-violet-700 hover:bg-violet-100 focus:outline-none hover:border-violet-700 cursor-pointer rounded-md p-1"
                >
                    {metadata.allowed_per_page.map((option) => (
                        <option key={option.value} value={option.value}>
                            {option.label}
                        </option>
                    ))}
                </select>
            </div>
        </div>
    );
}
