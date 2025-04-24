 

<?php $__env->startSection('title'); ?> Ajouter un Cours <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/dropzone.css')); ?>">
    <link href="<?php echo e(asset('assets/css/MonCss/custom-style.css')); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="<?php echo e(asset('assets/css/MonCss/SweatAlert2.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/custom-calendar.css')); ?>">

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $selectedFormationId = request()->query('training_id', old('training_id'));
?>
 
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Nouveau cours</h5>
                        <span>Complétez les informations pour créer un nouveau cours</span>
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
                        
                        <!-- Alerte d'information sur le calcul automatique de la durée -->
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>
                            <strong>Note:</strong> La durée du cours sera calculée automatiquement en fonction des chapitres que vous ajouterez ultérieurement.
                        </div>

                        <div class="form theme-form">
                            <form action="<?php echo e(route('coursstore')); ?>" method="POST" class="needs-validation" novalidate>
                                <?php echo csrf_field(); ?>

                                <!-- Titre -->
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Titre <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                            <input class="form-control" type="text" id="title" name="title" placeholder="Titre" value="<?php echo e(old('title')); ?>" required />
                                        </div>
                                        <div class="invalid-feedback">Veuillez entrer un titre valide.</div>
                                    </div>
                                </div>

                                <!-- Description -->
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

                                <!-- Dates de début et de fin -->
                                
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Périodes <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <!-- Date de début -->
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    <input class="form-control datepicker" type="text" id="start_date" name="start_date" placeholder="" value="<?php echo e(old('start_date')); ?>" readonly required />
                                                </div>
                                                <small class="text-muted">Date début</small>
                                                <div class="invalid-feedback">Veuillez sélectionner une date de début valide.</div>
                                            </div>
                                            <!-- Date de fin -->
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    <input class="form-control datepicker" type="text" id="end_date" name="end_date" placeholder="" value="<?php echo e(old('end_date')); ?>" readonly required />
                                                </div>
                                                <small class="text-muted">Date fin</small>
                                                <div class="invalid-feedback">Veuillez sélectionner une date de fin valide.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Formation <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-auto">
                                                <span class="input-group-text"><i class="fa fa-book"></i></span>
                                            </div>
                                            <div class="col">
                                                <?php
                                                    $fromUrl = session('from_url') ?? request()->has('from_url');
                                                ?>
                                                
                                                <?php if($selectedFormationId && ($fromUrl || request()->has('training_id'))): ?>
                                                    <?php
                                                        $selectedFormation = $formations->firstWhere('id', $selectedFormationId);
                                                    ?>
                                                        <!-- Mode URL : Affichage verrouillé -->

                                                    <input type="text" class="form-control bg-light selected-course-bg" value="<?php echo e($selectedFormation ? $selectedFormation->title : ''); ?>" readonly />
                                                    <input type="hidden" name="training_id" value="<?php echo e($selectedFormationId); ?>">
                                                    <input type="hidden" name="from_url" value="true">
                                                <?php elseif($selectedFormationId): ?>
                                                    <!-- Mode normal : Sélection libre -->

                                                    <select class="form-select select2-formation" name="training_id" required>
                                                        <option value="" disabled>Choisir une formation</option>
                                                        <?php $__currentLoopData = $formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($formation->id); ?>" <?php echo e($selectedFormationId == $formation->id ? 'selected' : ''); ?> class="<?php echo e($selectedFormationId == $formation->id ? 'selected-course-bg' : ''); ?>">
                                                                <?php echo e($formation->title); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php else: ?>
                                                    <select class="form-select select2-formation" name="training_id" required>
                                                        <option value="" disabled selected>Choisir une formation</option>
                                                        <?php $__currentLoopData = $formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($formation->id); ?>">
                                                                <?php echo e($formation->title); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">Veuillez sélectionner une formation valide.</div>
                                    </div>
                                </div>
                               
                                <!-- Boutons de soumission -->
                                <div class="row">
                                    <div class="col">
                                        <div class="text-end mt-4">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fa fa-save"></i> Ajouter
                                            </button>
                                            <button class="btn btn-danger" type="button" onclick="window.location.href='<?php echo e(route('cours')); ?>'">
                                                <i class="fa fa-times"></i> Annuler
                                            </button>
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
    <script src="<?php echo e(asset('assets/js/dropzone/dropzone.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dropzone/dropzone-script.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/select2-init/single-select.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/form-validation/form-validation.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/description/description.js')); ?>"></script>
    <script src="https://cdn.tiny.cloud/1/cwjxs6s7k08kvxb3t6udodzrwpomhxtehiozsu4fem2igekf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.fr.min.js"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/calendar/custom-calendar.js')); ?>"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Obtenir les paramètres d'URL
    const urlParams = new URLSearchParams(window.location.search);
    const trainingIdFromUrl = urlParams.get('training_id');
    const fromUrl = urlParams.get('from_url');
    
    // Gestion de la notification après l'ajout du cours
    let coursId = "<?php echo e(session('cours_id')); ?>";
    let fromUrlSession = "<?php echo e(session('from_url')); ?>";
    
    if (coursId) {
        Swal.fire({
            title: "Cours ajouté avec succès !",
            text: "Voulez-vous ajouter un chapitre à ce cours ? (La durée du cours sera calculée automatiquement)",
            icon: "success",
            showCancelButton: true,
            confirmButtonText: "Oui, ajouter un chapitre",
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
                // Si le cours vient d'une URL spécifique, conserver le paramètre from_url
                if (fromUrlSession === '1' || fromUrl === 'true') {
                    window.location.href = "<?php echo e(route('chapitrecreate')); ?>?cours_id=" + coursId + "&from_url=true";
                } else {
                    window.location.href = "<?php echo e(route('chapitrecreate')); ?>?cours_id=" + coursId;
                }
            } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                // Si le cours vient d'une URL spécifique, conserver le paramètre from_url
                if (fromUrlSession === '1' || fromUrl === 'true') {
                    window.location.href = "<?php echo e(route('cours')); ?>?from_url=true";
                } else {
                    window.location.href = "<?php echo e(route('cours')); ?>";
                }
            }
        });
    }

    // Appliquer le fond bleu à l'option sélectionnée dans le dropdown de Select2
    const coursSelect = document.querySelector('.select2-formation');
    if (coursSelect) {
        // Appliquer le fond bleu à l'option sélectionnée au chargement de la page
        const selectedOption = coursSelect.options[coursSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            selectedOption.classList.add('selected-course-bg');
        }

        // Appliquer le fond bleu à l'option sélectionnée lorsqu'elle change
        coursSelect.addEventListener('change', function() {
            // Supprimer la classe de l'ancienne option sélectionnée
            const previousSelectedOption = coursSelect.querySelector('.selected-course-bg');
            if (previousSelectedOption) {
                previousSelectedOption.classList.remove('selected-course-bg');
            }

            // Ajouter la classe à la nouvelle option sélectionnée
            const newSelectedOption = coursSelect.options[coursSelect.selectedIndex];
            if (newSelectedOption && newSelectedOption.value) {
                newSelectedOption.classList.add('selected-course-bg');
            }
        });
    }
});
   
</script>
<?php $__env->stopPush(); ?> 










<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/admin/apps/cours/courscreate.blade.php ENDPATH**/ ?>