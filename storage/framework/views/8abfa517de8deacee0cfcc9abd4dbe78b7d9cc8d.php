<?php $__env->startSection('title'); ?> Ajouter une Formation <?php $__env->stopSection(); ?>
<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/formationcreate.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/dropzone.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/formation-create.css')); ?>">
<link href="<?php echo e(asset('assets/css/MonCss/custom-style.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/MonCss/SweatAlert2.css')); ?>" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/custom-calendar.css')); ?>">

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Nouvelle formation</h5>
                        <span>Complétez les informations pour créer une nouvelle formation</span>
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
                            <strong>Note:</strong> La durée sera calculée automatiquement en fonction des cours ajoutés à cette formation.
                        </div>


                        <div class="form theme-form">
                            <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

                            <form id="formationForm"class="needs-validation" action="<?php echo e(route('formationstore')); ?>" method="POST" enctype="multipart/form-data" novalidate>
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col">
                                        <!-- Titre -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Titre <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                                    <input class="form-control" type="text" id="title" name="title" placeholder="Titre" value="<?php echo e(old('title')); ?>" required />
                                                </div>
                                                <div class="invalid-feedback">Veuillez entrer un Titre valide.</div>
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
                                        </div>                              <!-- Type -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Type <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-list"></i></span>
                                                    <select class="form-select" id="type" name="type" required>
                                                        <option value="" selected disabled>Choisir un type</option>
                                                        <option value="payante" <?php echo e(old('type') == 'payante' ? 'selected' : ''); ?>>Payante</option>
                                                        <option value="gratuite" <?php echo e(old('type') == 'gratuite' ? 'selected' : ''); ?>>Gratuite</option>
                                                    </select>
                                                </div>
                                                <div class="invalid-feedback">Veuillez sélectionner un type.</div>
                                            </div>
                                        </div>

                                        <!-- Prix -->
                                        <div class="mb-3 row" id="priceContainer">
                                            <label class="col-sm-2 col-form-label">Prix <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text">Dt</span>
                                                    <input class="form-control"
                                                           type="number"
                                                           id="price"
                                                           name="price"
                                                           placeholder="Ex: 50.000"
                                                           step="0.001"
                                                           min="0"
                                                           value="<?php echo e(old('price')); ?>" />
                                                </div>
                                                <small class="text-muted">Format: 000.000 (3 décimales obligatoires)</small>
                                                <div class="invalid-feedback">Veuillez entrer un prix valide (ex: 50.000 ou 45.500)</div>
                                            </div>
                                        </div>

                                        <!-- Catégorie -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Catégorie <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <select class="form-select select2-categorie" id="categorie_id" name="category_id" required>
                                                        <option value="" selected disabled>Choisir une catégorie</option>
                                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($categorie->id); ?>" <?php echo e(old('category_id') == $categorie->id ? 'selected' : ''); ?>>
                                                                <?php echo e($categorie->title); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="invalid-feedback">Veuillez sélectionner une catégorie valide.</div>
                                            </div>
                                        </div>

                                        <!-- Professeur -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Professeur <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                    </div>
                                                    <div class="col">
                                                        <select id="user_id" class="form-select select2-professeur" name="user_id" required>
                                                            <option value="" disabled selected>Sélectionnez un professeur</option>
                                                            <?php $__currentLoopData = $professeurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $professeur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($professeur->id); ?>" <?php echo e(old('user_id') == $professeur->id ? 'selected' : ''); ?>>
                                                                    <?php echo e($professeur->name); ?> <?php echo e($professeur->lastname ?? ''); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">Veuillez sélectionner un professeur valide.</div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Nombre de places <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-users"></i></span>
                                                    <input class="form-control"
                                                           type="number"
                                                           id="total_seats"
                                                           name="total_seats"
                                                           placeholder="Ex: 20"
                                                           min="1"
                                                           value="<?php echo e(old('total_seats')); ?>"
                                                           required />
                                                </div>
                                                <small class="text-muted">Nombre maximum de participants</small>
                                                <div class="invalid-feedback">Veuillez entrer un nombre de places valide (minimum 1).</div>
                                            </div>
                                        </div>



                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Image <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <?php if(session('success') && session('old_data') && isset(session('old_data')['image'])): ?>
                                                    <!-- Afficher uniquement l'image après création réussie -->
                                                    <div class="mt-3 text-center">
                                                        <img src="<?php echo e(asset('storage/' . session('old_data')['image'])); ?>"
                                                             class="img-fluid border rounded mx-auto"
                                                             alt="Aperçu de l'image"
                                                             style="display: block; width: auto; height: auto; max-width: 80%; min-height: 200px; margin: 0 auto;">
                                                        <input type="hidden" name="current_image" value="<?php echo e(session('old_data')['image']); ?>">
                                                        <input type="hidden" name="keep_image" value="1">
                                                    </div>
                                                <?php else: ?>
                                                    <!-- Afficher l'interface normale d'upload pendant la création -->
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fa fa-image"></i></span>
                                                        <input class="form-control" type="file" id="image" name="image" accept="image/*" required />
                                                    </div>
                                                    <div class="invalid-feedback">Veuillez télécharger une image valide.</div>
                                                    <small class="text-muted">Formats acceptés: JPG, PNG, GIF. Taille max: 2Mo</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>


                                        <!-- Conteneur de prévisualisation de l'image -->
                                        <div class="center-container">
                                            <div id="imagePreviewContainer" class="image-preview-container hidden">
                                                <img id="imagePreview" class="image-preview" src="#" alt="Prévisualisation de l'image" />
                                                <div class="image-preview-actions">
                                                    <button type="button" class="btn-icon" id="deleteImage">
                                                        <i class="fa fa-trash trash-icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Publication Section -->
                                        <div class="mb-3 row">
                                            <div class="col-12">
                                                <div class="d-flex justify-content-center">
                                                    <div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
                                                        <div class="radio radio-primary mx-2">
                                                            <input id="publishNow" type="radio" name="publication_type" value="now" checked>
                                                            <label class="mb-0" for="publishNow">Publier immédiatement</label>
                                                        </div>
                                                        <div class="radio radio-primary mx-2">
                                                            <input id="publishLater" type="radio" name="publication_type" value="later">
                                                            <label class="mb-0" for="publishLater">Programmer la publication</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Publication Date Container -->
                                                <div id="publishDateContainer" class="mt-3 text-center">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="input-group" style="max-width:500px;">
                                                            <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                                            <input class="form-control"
                                                                type="datetime-local"
                                                                id="publish_date"
                                                                name="publish_date"
                                                                value="<?php echo e(old('publish_date')); ?>"
                                                                min="<?php echo e(now()->format('Y-m-d\TH:i')); ?>">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Sélectionnez la date et l'heure de publication</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Boutons -->
                                        <div class="row">
                                            <div class="col">
                                                <div class="text-end mt-4">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa fa-save"></i> Ajouter
                                                    </button>
                                                    <button class="btn btn-danger" type="button" onclick="window.location.href='<?php echo e(route('formations')); ?>'">
                                                        <i class="fa fa-times"></i> Annuler
                                                    </button>
                                                </div>
                                            </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="<?php echo e(asset('assets/js/MonJs/select2-init/single-select.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/MonJs/form-validation/form-validation.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/MonJs/formations/formation-submit.js')); ?>"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="<?php echo e(asset('assets/css/MonCss/SweatAlert2.css')); ?>" rel="stylesheet">

<script src="<?php echo e(asset('assets/js/tinymce/js/tinymce/tinymce.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/MonJs/description/description.js')); ?>"></script>
<script src="https://cdn.tiny.cloud/1/8icondvsqzdfghi1bvctqkb2xdyxslqqmopagikz1byhtwuk/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Gestion de la notification après l'ajout du cours
    let formationId = "<?php echo e(session('formation_id')); ?>";
    if (formationId) {
        Swal.fire({
            // title: "Formation ajoutée avec succès !",
            title:"Formation ajouté avec succès !",

            text: "Voulez-vous ajouter un cours à cette formation ?",
            icon: "success",
            showCancelButton: true,
            confirmButtonText: "Oui, ajouter un cours",
            cancelButtonText: "Non, revenir à la liste",
            showCloseButton: false,
            allowOutsideClick: false,      // Empêche la fermeture en cliquant à l'extérieur
            allowEscapeKey: false,         // Empêche la fermeture avec la touche Échap
             allowEnterKey: false,
            customClass: {
                confirmButton: 'custom-confirm-btn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?php echo e(route('courscreate')); ?>?training_id=" + formationId;
            } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = "<?php echo e(route('formations')); ?>";
            }
        });
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.fr.min.js"></script>
<script src="<?php echo e(asset('assets/js/MonJs/calendar/custom-calendar.js')); ?>"></script>



<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/admin/apps/formation/formationcreate.blade.php ENDPATH**/ ?>