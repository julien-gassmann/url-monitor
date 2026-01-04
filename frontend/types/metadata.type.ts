export type MetadataPages = 'create-monitor' | 'paginate-checks';

export type CreateMonitorMetadata = {
    frequencies: { label: string }[];
    http_codes: Record<string, { code: number; message: string }[]>;
};

export type PaginateChecksMetadata = {
    allowed_per_page: {
        label: string;
        value: number;
    }[];
    // statuses: Record<string, string>; // { 'UP': 'En ligne', ... }
};

export type MetadataResponse<T extends MetadataPages> = T extends 'create-monitor'
    ? CreateMonitorMetadata
    : PaginateChecksMetadata;
