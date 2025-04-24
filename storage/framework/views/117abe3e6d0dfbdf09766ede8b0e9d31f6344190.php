<?php $__env->startSection('title'); ?> Ajouter un Chapitre <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/dropzone.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/select2.min.css')); ?>">
    <link href="<?php echo e(asset('assets/css/MonCss/custom-style.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/MonCss/SweatAlert2.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        // Récupérer la valeur du cours depuis l'URL OU la session OU old()
        $selectedCoursId = request()->query('cours_id') ?? session('cours_id') ?? old('course_id');
        $hasChapitreId = session()->has('chapitre_id');
    ?>




    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Nouveau chapitre</h5>
                        <span>Complétez les informations pour créer un nouveau chapitre</span>
                    </div>

                    <div class="card-body">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div class="mb-3 row">
                              <!-- Alerte d'information sur le calcul automatique de la durée -->
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>
                            <strong>Note:</strong>La durée sera calculée automatiquement à partir des leçons ajoutées à ce chapitre.
                        </div>
                        </div>
                            

                        <div class="form theme-form">
                            <form id="create-chapitre-form" class="needs-validation" action="<?php echo e(route('chapitrestore')); ?>" method="POST" novalidate>
                                <?php echo csrf_field(); ?>
     
                                <!-- Titre -->
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Titre <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                            <input class="form-control" type="text" name="title" placeholder="Titre" value="<?php echo e(old('title')); ?>" required />
                                        </div>
                                        <div class="invalid-feedback">Veuillez entrer un titre valide.</div>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Description <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="input-group" style="flex-wrap: nowrap;">
                                            <div class="input-group-text d-flex align-items-stretch" style="height: auto;">
                                                <i class="fa fa-align-left align-self-center"></i>
                                            </div>
                                            <textarea class="form-control" id="description" name="description" placeholder="Description" required><?php echo e(old('description')); ?></textarea>
                                        </div>
                                        <div class="invalid-feedback">Veuillez entrer une description valide.</div>
                                    </div>
                                </div>

                                <!-- Message informatif sur la durée -->
                              
                            
<?php
    // Déterminer la source du cours (URL ou sélection manuelle)
    $hasCoursIdFromUrl = request()->has('cours_id');
    $coursIdFromUrl = request()->query('cours_id');
    
    // Récupérer le coursId depuis différentes sources
    $coursIdFromSession = session('cours_id');
    $coursIdFromOld = old('course_id');
    
    // Déterminer la source du cours (important pour le comportement après redirection)
    $coursSource = $hasCoursIdFromUrl ? 'url' : session('cours_source', 'manual');
    
    // Si le cours vient de l'URL OU si la source enregistrée est URL, il doit être readonly
    $shouldBeReadonly = ($coursSource === 'url') && 
                        (!empty($coursIdFromUrl) || !empty($coursIdFromSession)) && 
                        isset($cours);
    
    // La valeur à utiliser pour le select ou l'affichage
    $selectedCoursId = $coursIdFromUrl ?? $coursIdFromSession ?? $coursIdFromOld;
    
    // Détecter si une SweetAlert va s'afficher (chapitre créé récemment)
    $hasChapitreId = session()->has('chapitre_id');
?>

<!-- Dans la section du formulaire où se trouve le champ cours -->
<div class="mb-3 row">
    <label class="col-sm-2 col-form-label">Cours <span class="text-danger">*</span></label>
    <div class="col-sm-10">
        <div class="row">
            <div class="col-auto">
                <span class="input-group-text"><i class="fa fa-book"></i></span>
            </div>
            <div class="col">
                <?php if($shouldBeReadonly): ?>
                    <!-- Cas 1: Si cours_id est dans l'URL ou si la source enregistrée est URL -->
                    <?php
                        $coursItem = $selectedCoursId ? $cours->find($selectedCoursId) : null;
                        $coursTitle = $coursItem ? $coursItem->title : '';
                    ?>
                    <input type="text" class="form-control bg-light selected-course-bg" value="<?php echo e($coursTitle); ?>" readonly />
                    <input type="hidden" name="course_id" value="<?php echo e($selectedCoursId); ?>">
                    <input type="hidden" name="cours_source" value="url">
                <?php else: ?>
                    <!-- Cas 2: Cours sélectionné manuellement, afficher le select -->
                    <select class="form-select select2-cours" name="course_id" required>
                        <option value="" disabled <?php echo e(!$selectedCoursId ? 'selected' : ''); ?>>Choisir un cours</option>
                        <?php $__currentLoopData = $cours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coursItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($coursItem->id); ?>" <?php echo e($selectedCoursId == $coursItem->id ? 'selected' : ''); ?>>
                                <?php echo e($coursItem->title); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <input type="hidden" name="cours_source" value="manual">
                <?php endif; ?>
                <div class="invalid-feedback">Veuillez sélectionner un cours valide.</div>
            </div>
        </div>
    </div>
</div>

                                <!-- Boutons de soumission -->
                                <div class="row">
                                    <div class="col">
                                        <div class="text-end mt-4">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fa fa-save"></i> Ajouter
                                            </button>
                                            <a href="<?php echo e(route('chapitres')); ?>" class="btn btn-danger px-4">
                                                <i class="fa fa-times"></i> Annuler
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/MonJs/dropzone/dropzone.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dropzone/dropzone-script.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/select2-init/single-select.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/form-validation/form-validation.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('assets/js/tinymce/js/tinymce/tinymce.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/description/description.js')); ?>"></script>
    <script src="https://cdn.tiny.cloud/1/cwjxs6s7k08kvxb3t6udodzrwpomhxtehiozsu4fem2igekf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
 
<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Votre code existant pour Select2...
    
    // Récupérer l'ID du chapitre et du cours depuis la session
    let chapitreId = "<?php echo e(session('chapitre_id')); ?>";
    let coursId = "<?php echo e(session('cours_id')); ?>";
    
    // Vérifier si l'ID du chapitre existe en session
    if (chapitreId) {
        Swal.fire({
            title: "Chapitre ajouté avec succès !",
            text: "Voulez-vous ajouter une lesson à ce chapitre ?",
            icon: "success",
            showCancelButton: true,
            confirmButtonText: "Oui, ajouter une lesson",
            cancelButtonText: "Non, revenir à la liste",
            showCloseButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
                confirmButton: 'custom-confirm-btn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Rediriger vers la création de leçon avec l'ID du chapitre
                window.location.href = "<?php echo e(route('lessoncreate')); ?>?chapitre_id=" + chapitreId;
            } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                // Rediriger vers la liste des chapitres
                window.location.href = "<?php echo e(route('chapitres')); ?>";
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/admin/apps/chapitre/chapitrecreate.blade.php ENDPATH**/ ?>