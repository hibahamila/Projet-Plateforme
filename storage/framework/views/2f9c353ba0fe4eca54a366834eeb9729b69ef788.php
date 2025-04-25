<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="border: none; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); padding: 25px; margin-top: 20px;">
                <div class="card-header text-center" style="background: transparent; border-bottom: 1px solid #eaeaea; padding: 15px 0 20px;">
                    <i class="fa fa-lock" style="font-size: 26px; color: #2B6ED4; margin-bottom: 15px;"></i>
                    <h4 style="font-weight: 600; font-size: 24px; color: #333; margin-bottom: 0;">Validation de votre compte</h4>
                </div>
                <div class="card-body" style="padding: 25px 20px 20px;">
                    <div style="margin-bottom: 25px; background-color: #f5f7fd; border-radius: 6px; padding: 15px; border-left: 3px solid #2B6ED4;">
                        <p style="color: #555; font-size: 15px; line-height: 1.5; margin: 0;">
                            <i class="fa fa-info-circle" style="color: #2B6ED4; margin-right: 8px;"></i>
                            Un code de validation a été envoyé à votre adresse e-mail.<br>
                            Veuillez entrer ce code pour activer votre compte.
                        </p>
                    </div>



                    <?php if(session('error')): ?>
                        <div class="alert alert-danger" style="border-radius: 6px; background-color: #fae3e5; color: #d62839; padding: 12px 15px; margin-bottom: 20px;">
                            <i class="fa fa-exclamation-circle" style="margin-right: 8px;"></i>
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>
                    <?php if(session('warning')): ?>
                        <div class="alert alert-warning" style="border-radius: 6px; background-color: #fff8e6; color: #b7791f; padding: 12px 15px; margin-bottom: 20px;">
                            <i class="fa fa-exclamation-triangle" style="margin-right: 8px;"></i>
                            <?php echo e(session('warning')); ?>

                        </div>
                    <?php endif; ?>

                    <!-- Formulaire de validation -->
                    <form method="POST" action="<?php echo e(route('validation.code')); ?>" id="validation-form">
                        <?php echo csrf_field(); ?>
                        <div class="form-group mb-4">
                            <label for="validation_code" style="display: none;">Code de validation</label>
                            <input type="hidden" id="validation_code" name="validation_code">

                            <p style="text-align: center; margin-bottom: 15px; font-size: 14px; color: #666;">
                                <i class="fa fa-paste" style="margin-right: 5px;"></i> Vous pouvez saisir ou coller votre code
                            </p>

                            <!-- Affichage des cases de code individuelles -->
                            <div style="display: flex; justify-content: space-between; gap: 8px; margin-bottom: 20px;">
                                <?php for($i = 1; $i <= 6; $i++): ?>
                                <div style="flex: 1;">
                                    <input type="text"
                                           class="code-input"
                                           style="width: 100%; height: 55px; text-align: center; font-size: 22px; font-weight: 500;
                                                  border-radius: 8px; border: 1px solid #cfd7e6; background-color: #f8faff;
                                                  box-shadow: inset 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s ease;
                                                  color: #2B6ED4; text-transform: none;"
                                           maxlength="1"
                                           data-index="<?php echo e($i); ?>"
                                           autocomplete="off">
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="text-center mb-3" style="margin-top: 25px;">
                            <button type="submit" class="btn"
                                    style="background-color: #2B6ED4; border: none; border-radius: 6px;
                                    width: 100%; padding: 14px; font-size: 16px; font-weight: 600; color: white;
                                    box-shadow: 0 2px 6px rgba(43, 110, 212, 0.3);">
                                <i class="fa fa-check" style="margin-right: 8px;"></i>
                                Valider mon compte
                            </button>
                        </div>
                    </form>

                    <div class="text-center" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eaeaea;">
                        <a href="<?php echo e(route('resend.code')); ?>"
                           style="color: #2B6ED4; text-decoration: none; font-size: 15px; font-weight: 500;">
                            <i class="fa fa-sync" style="margin-right: 5px;"></i>
                            Renvoyer un code
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInputs = document.querySelectorAll('.code-input');
    const validationInput = document.getElementById('validation_code');
    const form = document.querySelector('form');

    // Fonction pour mettre à jour le champ caché avec tous les codes
    function updateValidationCode() {
        let code = '';
        let isComplete = true;

        codeInputs.forEach(input => {
            // Vérifier si tous les champs sont remplis
            if (!input.value) {
                isComplete = false;
            }
            code += input.value;
        });

        validationInput.value = code;
        return isComplete;
    }
    form.addEventListener('submit', function(e) {
        // Mettre à jour le code caché
        const isComplete = updateValidationCode();

        // Si le code n'est pas complet, empêcher la soumission
        if (!isComplete) {
            e.preventDefault();
            // Optionnel : afficher un message d'erreur
            alert('Veuillez remplir tous les champs du code de validation');
        }
    });
    // Ajouter un gestionnaire global de collage pour tout le conteneur de code
    document.addEventListener('paste', function(e) {
        // Vérifier si un élément de saisie de code est actif ou à proximité
        if (document.activeElement && document.activeElement.classList.contains('code-input')) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            // Nettoyons le texte mais gardons les lettres et les chiffres
            const cleanedText = pastedText.replace(/[^A-Za-z0-9]/g, '').substring(0, 6);

            if (cleanedText.length > 0) {
                // Distribuer les caractères dans les cases
                for (let i = 0; i < codeInputs.length && i < cleanedText.length; i++) {
                    codeInputs[i].value = cleanedText[i];
                }

                updateValidationCode();

                // Focus sur la case après la dernière case remplie ou sur la dernière case
                const focusIndex = Math.min(cleanedText.length, codeInputs.length - 1);
                codeInputs[focusIndex].focus();
            }
        }
    });



    codeInputs.forEach((input, index) => {
        // Quand on entre un caractère
        input.addEventListener('input', function() {
            updateValidationCode();

            if (this.value.length === 1 && index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }
        });

        // Quand on utilise la touche retour arrière
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                codeInputs[index - 1].focus();
            }

            // Empêcher la soumission du formulaire si Entrée est pressée et le code est incomplet
            if (e.key === 'Enter') {
                e.preventDefault();
                const isComplete = updateValidationCode();
                if (!isComplete) {
                    alert('Veuillez remplir tous les champs du code de validation');
                } else {
                    form.submit();
                }
            }
        });


        // Aussi gérer le collage individuel sur chaque champ
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            // Gardons les lettres et les chiffres, sans conversion en majuscules
            const cleanedText = pastedText.replace(/[^A-Za-z0-9]/g, '').substring(0, 6);

            if (cleanedText.length === 1) {
                // Si un seul caractère est collé
                this.value = cleanedText;
                updateValidationCode();
                if (index < codeInputs.length - 1) {
                    codeInputs[index + 1].focus();
                }
            } else if (cleanedText.length > 1) {
                // Si plusieurs caractères sont collés, distribuer dans toutes les cases
                for (let i = 0; i < codeInputs.length && i < cleanedText.length; i++) {
                    codeInputs[i].value = cleanedText[i];
                }
                updateValidationCode();

                // Focus sur la case après la dernière case remplie ou sur la dernière case
                const focusIndex = Math.min(cleanedText.length, codeInputs.length - 1);
                codeInputs[focusIndex].focus();
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.authentication.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/auth/verify.blade.php ENDPATH**/ ?>