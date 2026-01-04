import type { MetadataPages, MetadataResponse } from '@/types/metadata.type';

import { apiGet } from './client';

const baseUrl = '/metadata';

export async function getMetadata<T extends MetadataPages>(forPage: T) {
    const url = `${baseUrl}/${forPage}`;
    const callId = 'get-metadata';
    return apiGet<MetadataResponse<T>>(url, callId);
}
