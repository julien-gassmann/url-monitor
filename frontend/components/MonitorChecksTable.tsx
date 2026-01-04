import React, { useMemo } from 'react';

import { useTableContext } from '@/contexts/TableContext';
import { useMetadata } from '@/hooks/monitorForm/useMetadata';
import { useIsPending } from '@/hooks/useIsPending';
import type { MonitorCheck, MonitorCheckFilters } from '@/types/monitor.type';
import type { ColumnDef } from '@tanstack/react-table';
import { getCoreRowModel, useReactTable } from '@tanstack/react-table';

import { TableBody } from '@/components/table/TableBody';
import { TableHeader } from '@/components/table/TableHeader';
import { TablePaginationNav } from '@/components/table/TablePaginationNav';
import { TablePaginationResult } from '@/components/table/TablePaginationResult';
import { CircularLoader } from '@/components/ui/CircularLoader';
import { TopLoadingBar } from '@/components/ui/TopLoadingBar';

export function MonitorChecksTable() {
    const metadata = useMetadata('paginate-checks');
    const { data } = useTableContext<MonitorCheckFilters>().pagination;
    const isPaginationLoading = useIsPending('get-monitor-checks', false);
    const isMetadataLoading = useIsPending('get-metadata', true);

    const columns = useMemo<ColumnDef<MonitorCheck>[]>(
        () => [
            { accessorKey: 'checked_at', header: 'Date & Heure' },
            { accessorKey: 'status', header: 'Statut' },
            { accessorKey: 'http_code', header: 'Code HTTP' },
        ],
        []
    );

    /* eslint-disable react-hooks/incompatible-library */
    const table = useReactTable({
        data,
        columns,
        getCoreRowModel: getCoreRowModel(),
        manualSorting: true,
        manualPagination: true,
    });

    if (isMetadataLoading) {
        return <CircularLoader />;
    }

    return (
        metadata && (
            <div className="relative overflow-hidden m-6 p-6  bg-white rounded-3xl font-bold text-gray-500 text-lg shadow-lg">
                <TopLoadingBar isLoading={isPaginationLoading} />
                {/* Header */}
                <div className="flex flex-col mb-8">
                    <h2 className="text-xl text-black">Historique des vérifications</h2>
                    <span>Liste chronologique de toutes les vérifications effectuées</span>
                </div>

                {/* Table */}
                <div className="overflow-hidden">
                    <table className="w-full text-left border-collapse">
                        <TableHeader<MonitorCheckFilters> table={table} />

                        <TableBody table={table} />
                    </table>
                </div>

                {/* Pagination */}
                <div className="flex items-center justify-between border-t border-gray-200 gap-2 pt-6">
                    <TablePaginationResult<MonitorCheckFilters> metadata={metadata} />

                    <TablePaginationNav<MonitorCheckFilters> />
                </div>
            </div>
        )
    );
}
