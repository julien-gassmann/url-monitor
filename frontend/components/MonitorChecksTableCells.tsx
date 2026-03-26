import { LuCircleCheckBig, LuCircleX, LuTriangleAlert } from 'react-icons/lu';

const STATUS_CONFIG = {
    UP: {
        icon: <LuCircleCheckBig />,
        className: 'bg-green-100 text-green-700',
    },
    DOWN: {
        icon: <LuCircleX />,
        className: 'bg-red-100 text-red-700',
    },
    UNREACHABLE: {
        icon: <LuTriangleAlert />,
        className: 'bg-amber-100 text-amber-700',
    },
} as const;

type Props = {
    value: string;
};

export function StatusCell({ value }: Props) {
    const config = STATUS_CONFIG[value as keyof typeof STATUS_CONFIG] ?? {
        icon: null,
        className: 'bg-gray-100 text-gray-500',
    };

    return (
        <span
            className={`inline-flex items-center gap-2 px-3 py-1 rounded-xl text-sm font-semibold ${config.className}`}
        >
            {config.icon}
            {value}
        </span>
    );
}

export function HttpCodeCell({ value }: Props) {
    return (
        <span className={value === 'N/A' ? 'text-gray-400 font-medium' : 'font-mono text-black'}>
            {value}
        </span>
    );
}

export function DateCell({ value }: Props) {
    return <span className="font-medium">{value}</span>;
}
