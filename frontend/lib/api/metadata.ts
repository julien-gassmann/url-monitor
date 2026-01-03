import { apiGet } from './client';

export type MetadataResponse = {
    frequencies: { label: string }[];
    http_codes: Record<string, { code: number; message: string }[]>;
    // statuses: Record<string, string>; // { 'UP': 'En ligne', ... }
};

const baseUrl = '/metadata';

export async function getMetadata() {
    const callId = 'get-metadata';
    return apiGet<MetadataResponse>(baseUrl, callId);
}
