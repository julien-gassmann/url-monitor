import toast from 'react-hot-toast';

export const appToast = {
    success(message: string) {
        toast.success(message);
    },

    error(message: string) {
        toast.error(message);
    },

    metadata: {
        failure() {
            toast.error('Impossible de charger les métadonnées.');
        },
    },

    monitor: {
        success() {
            toast.success('Surveillance créée avec succès.');
        },
        failure() {
            toast.error('Une erreur est survenue.');
        },
        unauthorized() {
            toast.error("Vous n'êtes pas autorisé.");
        },
    },

    verification: {
        success() {
            toast.success('Vérification réussie !');
        },
        failure() {
            toast.error('Le lien de vérification est invalide.');
        },
    },

    refresh: {
        success() {
            toast.success('Nouveau lien envoyé avec succès.');
        },
        failure() {
            toast.error("L'envoie du nouveau lien a échoué.");
        },
    },
};
