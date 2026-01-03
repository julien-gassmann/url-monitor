'use client';

import { useState } from 'react';

import { LuCheck, LuCopy } from 'react-icons/lu';

type UrlSnippetProps = {
    url: string;
};

export default function UrlSnippet({ url }: UrlSnippetProps) {
    const [copied, setCopied] = useState(false);

    const handleCopy = async () => {
        await navigator.clipboard.writeText(url);
        setCopied(true);
        setTimeout(() => setCopied(false), 1500);
    };

    return (
        <div
            onClick={handleCopy}
            className="relative overflow-hidden group cursor-pointer rounded-lg bg-gray-100 p-3 font-mono text-gray-900 shadow-sm hover:bg-gray-200 transition"
        >
            <span className="select-all text-black">{url}</span>

            <div className="absolute top-2 right-2 text-gray-500 group-hover:text-gray-700">
                {copied ? <LuCheck className="text-green-500" /> : <LuCopy />}
            </div>

            {copied && (
                <div className="absolute bottom-1 right-2 text-xs text-green-500">Copié !</div>
            )}
        </div>
    );
}
