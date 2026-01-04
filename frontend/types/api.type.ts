export type ApiResponse<R, E> = {
    ok: boolean;
    status: number;
    data?: R;
    errors?: E;
    message?: string;
    links?: PaginationLinks;
    meta?: PaginationMeta;
};

type PaginationLinks = {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
};

export type PaginationMeta = {
    current_page: number;
    from: number | null;
    last_page: number;
    path: string;
    per_page: number;
    to: number | null;
    total: number;
};

export type Pagination<T> = {
    data: T[];
    links: PaginationLinks;
    meta: PaginationMeta;
};

export type PaginationFilters<S, F> = {
    page: number;
    per_page: AllowedPerPageCounts;
    sort?: S;
    filter?: F;
};

export type PaginationErrors<T> = {
    page?: string[];
    per_page?: string[];
} & T;

export type AllowedPerPageCounts = 1 | 5 | 10 | 25 | 50 | 100 | -1;
