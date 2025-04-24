
/**
 * Système de notification toast
 * Permet d'afficher des notifications stylisées temporaires avec timeline
 */

// Configuration par défaut
const ToastConfig = {
    duration: 5000,       // Durée d'affichage en ms
    position: 'top-end',  // Position d'affichage
    closeButton: true,    // Afficher un bouton de fermeture
    animation: true,      // Animer le toast
    timeline: true,       // Afficher la timeline
    containerClass: 'toast-container',
    toastClass: 'custom-toast',
    // Couleurs personnalisables pour chaque type
    colors: {
        success: {
            background: '#FFFFFF',    // Fond blanc
            text: '#2B6ED4',          // Texte bleu
            timeline: '#2B6ED4'       // Timeline bleue
        },
        error: {
            background: '#FFFFFF',    // Fond blanc
            text: '#F44336',          // Texte rouge
            timeline: '#F44336'       // Timeline rouge
        },
        warning: {
            background: '#FFFFFF',    // Fond blanc
            text: '#FF9800',          // Texte orange
            timeline: '#FF9800'       // Timeline orange
        },
        info: {
            background: '#FFFFFF',    // Fond blanc
            text: '#2196F3',          // Texte bleu clair
            timeline: '#2196F3'       // Timeline bleue
        }
    }
};

class ToastNotification {
    /**
     * Initialise le système de notification
     */
    constructor(options = {}) {
        // Fusion des options avec les valeurs par défaut
        this.options = { ...ToastConfig, ...options };
        
        // Fusion des couleurs si fournies
        if (options.colors) {
            this.options.colors = {
                ...ToastConfig.colors,
                ...options.colors
            };
            
            // Fusion des couleurs pour chaque type
            for (const type in this.options.colors) {
                if (options.colors[type]) {
                    this.options.colors[type] = {
                        ...ToastConfig.colors[type],
                        ...options.colors[type]
                    };
                }
            }
        }
        
        this.initContainer();
        
        // Nous gardons l'écouteur global pour la compatibilité, mais il sera moins utilisé
        document.addEventListener('click', (e) => {
            if (e.target && e.target.hasAttribute('data-toast-close')) {
                const toastElement = e.target.closest(`.${this.options.toastClass}`);
                if (toastElement) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.close(toastElement);
                    return false;
                }
            }
        }, true);
    }

    /**
     * Initialise le conteneur de toasts s'il n'existe pas déjà
     */
    initContainer() {
        if (!document.querySelector(`.${this.options.containerClass}`)) {
            const container = document.createElement('div');
            container.className = this.options.containerClass;
            
            // Positionnement du conteneur
            container.style.position = 'fixed';
            container.style.zIndex = '9999';
            
            // Position par défaut: top-end
            container.style.top = '20px';
            container.style.right = '20px';
            
            // Si position différente
            if (this.options.position.includes('bottom')) {
                container.style.top = 'auto';
                container.style.bottom = '20px';
            }
            if (this.options.position.includes('start')) {
                container.style.right = 'auto';
                container.style.left = '20px';
            }
            
            document.body.appendChild(container);
        }
    }

    /**
     * Affiche un toast
     * @param {string} message - Message à afficher
     * @param {string} type - Type de toast (success, error, warning, info)
     * @param {object} options - Options supplémentaires
     */
    show(message, type = 'success', options = {}) {
        const toastOptions = { ...this.options, ...options };
        const container = document.querySelector(`.${this.options.containerClass}`);
        
        // Créer le toast
        const toast = document.createElement('div');
        toast.className = `${this.options.toastClass} toast-${type}`;
        toast.style.opacity = '0';
        toast.style.transition = 'all 0.3s ease-in-out';
        toast.style.marginBottom = '10px';
        toast.style.borderRadius = '4px';
        toast.style.padding = '15px 20px';
        toast.style.display = 'flex';
        toast.style.flexDirection = 'column';
        toast.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        toast.style.width = '300px';
        toast.style.boxSizing = 'border-box';
        toast.style.position = 'relative';
        toast.style.overflow = 'hidden';
        toast.style.pointerEvents = 'auto'; // S'assurer que le toast capte les événements
        
        // Style selon le type en utilisant les couleurs configurées
        if (this.options.colors[type]) {
            toast.style.backgroundColor = this.options.colors[type].background;
            toast.style.color = this.options.colors[type].text;
        }
        
        // Conteneur principal pour le contenu du toast
        const contentContainer = document.createElement('div');
        contentContainer.style.display = 'flex';
        contentContainer.style.alignItems = 'center';
        contentContainer.style.width = '100%';
        
        // Contenu du toast
        const icon = document.createElement('span');
        icon.style.marginRight = '10px';
        icon.style.fontSize = '18px';
        icon.style.color = this.options.colors[type].text; // Couleur de l'icône
        
        // Définir l'icône en fonction du type
        if (type === 'success') icon.innerHTML = '<i class="fa fa-check-circle"></i>';
        else if (type === 'error') icon.innerHTML = '<i class="fa fa-times-circle"></i>';
        else if (type === 'warning') icon.innerHTML = '<i class="fa fa-exclamation-triangle"></i>';
        else if (type === 'info') icon.innerHTML = '<i class="fa fa-info-circle"></i>';
        
        const content = document.createElement('div');
        content.style.flex = '1';
        content.style.wordBreak = 'break-word';
        content.innerHTML = message;
        
        contentContainer.appendChild(icon);
        contentContainer.appendChild(content);
        
        // Bouton de fermeture avec logique améliorée
        if (toastOptions.closeButton) {
            const closeBtn = document.createElement('span');
            closeBtn.innerHTML = '×';
            closeBtn.style.marginLeft = '10px';
            closeBtn.style.cursor = 'pointer';
            closeBtn.style.fontSize = '22px';
            closeBtn.style.fontWeight = 'bold';
            closeBtn.style.opacity = '0.7';
            closeBtn.style.color = this.options.colors[type].text;
            closeBtn.style.position = 'relative'; // Position relative pour l'isoler
            closeBtn.style.zIndex = '10000'; // Z-index plus élevé
            closeBtn.style.width = '30px'; // Largeur définie pour une meilleure zone de clic
            closeBtn.style.height = '30px'; // Hauteur définie pour une meilleure zone de clic
            closeBtn.style.textAlign = 'center'; // Centrer le X
            closeBtn.style.lineHeight = '25px'; // Alignement vertical du X
            closeBtn.setAttribute('data-toast-close', 'true');
            
            // Style au survol
            closeBtn.addEventListener('mouseover', function() {
                this.style.opacity = '1'; 
            });
            
            closeBtn.addEventListener('mouseout', function() {
                this.style.opacity = '0.7'; 
            });
            
            // Gestionnaire d'événement isolé avec meilleure capture
            const self = this; // Préservation du contexte
            const clickHandler = function(event) {
                event.stopPropagation();
                event.preventDefault();
                event.stopImmediatePropagation(); // Arrête également les autres gestionnaires
                self.close(toast);
                return false; // Empêche la propagation
            };
            
            // Ajouter plusieurs écouteurs pour s'assurer que l'événement est capturé
            closeBtn.addEventListener('click', clickHandler, true);
            closeBtn.addEventListener('mousedown', clickHandler, true);
            closeBtn.addEventListener('mouseup', clickHandler, true);
            
            contentContainer.appendChild(closeBtn);
        }
        
        toast.appendChild(contentContainer);
        
        // Ajouter la timeline si activée
        if (toastOptions.timeline && toastOptions.duration > 0) {
            const timeline = document.createElement('div');
            timeline.className = 'toast-timeline';
            timeline.style.position = 'absolute';
            timeline.style.bottom = '0';
            timeline.style.left = '0';
            timeline.style.height = '3px';
            timeline.style.width = '100%';
            timeline.style.backgroundColor = 'rgba(0, 0, 0, 0.1)'; // Fond de timeline légèrement grisé
            
            const progress = document.createElement('div');
            progress.style.height = '100%';
            progress.style.width = '0%'; // Commence à 0% (gauche)
            progress.style.backgroundColor = this.options.colors[type].timeline; // Couleur de la timeline selon le type
            progress.style.transition = `width ${toastOptions.duration}ms linear`;
            
            timeline.appendChild(progress);
            toast.appendChild(timeline);
            
            // Animation de la timeline - de gauche à droite
            setTimeout(() => {
                progress.style.width = '100%'; // Se déplace jusqu'à 100% (droite)
            }, 50);
        }
        
        // Ajouter le toast au conteneur
        container.appendChild(toast);
        
        // Animation d'entrée
        setTimeout(() => {
            toast.style.opacity = '1';
        }, 50);
        
        // Fermeture automatique après la durée spécifiée
        if (toastOptions.duration > 0) {
            setTimeout(() => {
                this.close(toast);
            }, toastOptions.duration);
        }
        
        return toast;
    }
    
    /**
     * Ferme un toast
     * @param {HTMLElement} toast - Élément toast à fermer
     */
    close(toast) {
        console.log("Fermeture du toast"); // Debug
        
        if (!toast) return; // Sécurité
        
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(20px)';
        
        // Supprimer l'élément après l'animation
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
                console.log("Toast supprimé du DOM"); // Debug
            }
        }, 300);
    }
    
    /**
     * Raccourcis pour les différents types de toast
     */
    success(message, options = {}) {
        return this.show(message, 'success', options);
    }
    
    error(message, options = {}) {
        return this.show(message, 'error', options);
    }
    
    warning(message, options = {}) {
        return this.show(message, 'warning', options);
    }
    
    info(message, options = {}) {
        return this.show(message, 'info', options);
    }
}

// Créer une instance globale
const toast = new ToastNotification();