'use client';

import { useEffect, useMemo, useState } from 'react';

import { getMetadata } from '@/lib/api/metadata';
import { appToast } from '@/lib/toast';
import type { MetadataPages, MetadataResponse } from '@/types/metadata.type';

export function useMetadata<T extends MetadataPages>(forPage: T) {
    const [metadata, setMetadata] = useState<MetadataResponse<T> | null>(null);

    useEffect(() => {
        getMetadata<T>(forPage).then((response) =>
            response.ok ? setMetadata(response.data) : appToast.metadata.failure()
        );
    }, [forPage]);

    return useMemo(() => metadata, [metadata]);
}
