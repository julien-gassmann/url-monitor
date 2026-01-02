import { apiGet } from './client';

export type MetadataResponse = {
    frequencies: { label: string }[];
    http_codes: Record<string, { code: number; message: string }[]>;
    // statuses: Record<string, string>; // { 'UP': 'En ligne', ... }
};

export async function getMetadata() {
    return apiGet<MetadataResponse>('/metadata');
}
