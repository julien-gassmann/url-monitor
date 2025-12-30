import {CollapseTransition} from "@/components/ui/CollapseTransition";

type FormErrorMessageProps = {
    messages?: string[]
    onClose: () => void
}

export function FormErrorMessage({ messages, onClose }: FormErrorMessageProps) {
    const hasMessages = messages && messages.length > 0;

    return (
        <CollapseTransition show={!!hasMessages}>
            <div className="relative text-sm text-red-400 p-2 mt-2 bg-red-400/10 rounded-lg">
                {messages?.map((msg, index) => (
                    <div key={index} className="mb-1">
                        {msg}
                    </div>
                ))}

                <button
                    type="button"
                    className="absolute top-2 right-2 text-red-400"
                    onClick={onClose}
                >
                    <svg
                        viewBox="0 0 512 512"
                        className="close-error absolute top-1/2 right-1/2 size-3 fill-red-400 hover:cursor-pointer"
                    >
                        <path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c-9.4 9.4-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0z"/>
                    </svg>
                </button>
            </div>
        </CollapseTransition>
    )
}