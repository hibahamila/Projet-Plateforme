<?php $__env->startSection('title'); ?>Compte Bloqué <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .auth-bg {
        background-color: #f8f9fa;
    }
    .blocked-icon {
        font-size: 4rem;
        color: #dc3545;
    }
    .contact-info {
    background-color: #2B6ED4; /* Fond bleu */
    color: white; /* Texte blanc */
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    }
    .contact-info h5, .contact-info p, .contact-info strong {
        color: white; /* Assure que tout le texte est blanc */
    }  margin-bottom: 5px;

</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-user-lock blocked-icon"></i>
                    </div>
                    <h2 class="mb-4">Compte Bloqué</h2>
                    <p class="lead mb-4">Votre compte a été bloqué suite à plusieurs tentatives de connexion échouées.</p>
                    <p class="mb-4">Pour débloquer votre compte, veuillez contacter l'administrateur système.</p>

                    <div style="background-color: #2B6ED4; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <h5 style="color: white;"><i class="fas fa-address-card me-2"></i>Contacter l'Administrateur:</h5>
                        <p><i class="fas fa-envelope me-2"></i>Adresse email: <strong>els.center2022@gmail.com</strong></p>
                        <p><i class="fas fa-phone me-2"></i>Téléphone: <strong>52450193</strong></p>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la page de connexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.authentication.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/auth/blocked.blade.php ENDPATH**/ ?>