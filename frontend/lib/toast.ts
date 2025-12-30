import toast from 'react-hot-toast';

export const appToast = {
    success(message: string) {
        toast.success(message);
    },

    error(message: string) {
        toast.error(message);
    },

    info(message: string) {
        toast(message);
    },

    monitor: {
        created() {
            toast.success('Surveillance créée avec succès.');
        },
        failed() {
            toast.error('Erreur lors de la création de la surveillance.');
        },
    },
};