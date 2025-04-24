// (Gère les menus d'actions et les interactions de modification/suppression)

function attachActionIconHandlers() {
    document.querySelectorAll('.action-dots').forEach(dots => {
        dots.addEventListener('click', function(e) {
            e.stopPropagation();
            
            $('#formation-detail-panel').removeClass('active').css('opacity', 0);
            
            document.querySelectorAll('.action-dropdown.show').forEach(dropdown => {
                if (!dropdown.parentNode.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
            
            const dropdown = this.nextElementSibling;
            dropdown.classList.toggle('show');
        });
    });
    
    document.querySelectorAll('.edit-action').forEach(action => {
        action.addEventListener('click', function(e) {
            e.stopPropagation();
            window.location.href = this.dataset.editUrl;
        });
    });
    
    document.querySelectorAll('.delete-action').forEach(action => {
        action.addEventListener('click', function(e) {
            e.stopPropagation();
            const deleteUrl = this.dataset.deleteUrl;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            if (confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                const tokenField = document.createElement('input');
                tokenField.type = 'hidden';
                tokenField.name = '_token';
                tokenField.value = csrfToken;
                
                form.appendChild(methodField);
                form.appendChild(tokenField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
}
function initActionMenus() {
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
    attachActionIconHandlers();
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    initActionMenus();
});