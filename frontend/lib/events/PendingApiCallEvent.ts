type Listener = (_callId: string, _isPending: boolean) => void;

const listeners = new Set<Listener>();
const activeCalls = new Set<string>();

export function emitPendingApiCall(callId: string, isStarting: boolean) {
    if (isStarting) {
        activeCalls.add(callId);
    } else {
        activeCalls.delete(callId);
    }

    listeners.forEach((listener) => listener(callId, isStarting));
}

export function onPendingApiCall(listener: Listener) {
    listeners.add(listener);
    const unsubscribe = () => {
        listeners.delete(listener);
    };

    return () => unsubscribe();
}
