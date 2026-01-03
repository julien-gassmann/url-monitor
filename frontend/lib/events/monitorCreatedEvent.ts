type Listener = () => void;

const listeners = new Set<Listener>();

export function emitMonitorCreatedEvent() {
    listeners.forEach((listener) => listener());
}

export function onMonitorCreatedEvent(listener: Listener) {
    listeners.add(listener);
    const unsubscribe = () => {
        listeners.delete(listener);
    };

    return () => unsubscribe();
}
