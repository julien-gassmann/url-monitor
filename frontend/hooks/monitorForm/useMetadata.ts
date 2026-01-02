import { useEffect, useState } from 'react';

import { type MetadataResponse, getMetadata } from '@/lib/api/metadata';
import { appToast } from '@/lib/toast';

export function useMetadata() {
    const [metadata, setMetadata] = useState<MetadataResponse | null>(null);

    useEffect(() => {
        getMetadata().then((response) =>
            response.ok && response.data ? setMetadata(response.data) : appToast.metadata.failure()
        );
    }, []);

    return metadata;
}
