<?php $__env->startSection('title'); ?> Modifier une Formation <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/dropzone.css')); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/formationedit.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/custom-calendar.css')); ?>">

    
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Modifier une formation</h5>
                        <span>Complétez les informations pour modifier la formation</span>
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
                        <div class="form theme-form">
                            <form class="needs-validation" action="<?php echo e(route('formationupdate', $formation->id)); ?>" method="POST" enctype="multipart/form-data" novalidate>
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="row">
                                    <div class="col">
                                        <!-- Titre -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Titre <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-tag"></i></span>
                                                    <input class="form-control" type="text" id="title" name="title" placeholder="Titre" value="<?php echo e(old('title', $formation->title)); ?>" required />
                                                </div>
                                                <div class="invalid-feedback">Veuillez entrer un Titre valide.</div>
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
                                                    <textarea class="form-control" id="description" name="description" placeholder="Description" required><?php echo e(old('description',$formation->description)); ?></textarea>
                                                </div>
                                                <div class="invalid-feedback">Veuillez entrer une description valide.</div>
                                            </div>
                                        </div>
                                        <!-- Dates de début et fin -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Périodes <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="date-input-group">
                                                    <div class="date-input-container">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                            <input class="form-control datepicker" type="text" id="start_date" name="start_date" 
                                                                value="<?php echo e(old('start_date', \Carbon\Carbon::parse($formation->start_date)->format('d/m/Y'))); ?>" required />
                                                        </div>
                                                        <small class="text-muted">Date de début</small>
                                                    </div>
                                                    <div class="date-input-container">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                            <input class="form-control datepicker" type="text" id="end_date" name="end_date" 
                                                                value="<?php echo e(old('end_date', \Carbon\Carbon::parse($formation->end_date)->format('d/m/Y'))); ?>" required />
                                                        </div>
                                                        <small class="text-muted">Date de fin</small>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">Veuillez entrer des dates valides (la date de fin doit être après la date de début).</div>
                                            </div>
                                        </div>
                                        <!-- Type -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Type <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-list"></i></span>
                                                    <select class="form-select" id="type" name="type" required>
                                                        <option value="payante" <?php echo e(old('type', $formation->type) == 'payante' ? 'selected' : ''); ?>>Payante</option>
                                                        <option value="gratuite" <?php echo e(old('type', $formation->type) == 'gratuite' ? 'selected' : ''); ?>>Gratuite</option>
                                                    </select>
                                                </div>
                                                <div class="invalid-feedback">Veuillez sélectionner un type.</div>
                                            </div>
                                        </div>

                                        <!-- Prix -->
                                        <div class="mb-3 row" id="priceContainer" style="<?php echo e(old('type', $formation->type) == 'payante' ? 'display: flex;' : 'display: none;'); ?>">
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
                                                           value="<?php echo e(old('price', $formation->price)); ?>" />
                                                </div>
                                                <small class="text-muted">Format: 000.000 (3 décimales obligatoires)</small>
                                                <div class="invalid-feedback">Veuillez entrer un prix valide (ex: 50.000 ou 45.500)</div>
                                            </div>
                                        </div>

                                        <!-- Remise -->
                                        <div class="mb-3 row" id="discountContainer" style="<?php echo e(old('type', $formation->type) == 'payante' ? 'display: flex;' : 'display: none;'); ?>">
                                            <label class="col-sm-2 col-form-label">Remise (%)</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-percent"></i></span>
                                                    <input class="form-control" 
                                                           type="number" 
                                                           id="discount" 
                                                           name="discount" 
                                                           placeholder="Ex: 10" 
                                                           min="0" 
                                                           max="100"
                                                           value="<?php echo e(old('discount', $formation->discount ?? 0)); ?>" />
                                                </div>
                                                <small class="text-muted">Entrez un pourcentage de remise (0-100)</small>
                                            </div>
                                        </div>

                                        <!-- Prix final -->
                                        <div class="mb-3 row" id="finalPriceContainer" style="<?php echo e(old('type', $formation->type) == 'payante' ? 'display: flex;' : 'display: none;'); ?>">
                                            <label class="col-sm-2 col-form-label">Prix final</label>
                                            <div class="col-sm-10">
                                                <div class="price-display">
                                                    <span class="original-price" id="originalPriceDisplay"><?php echo e(number_format($formation->price, 3)); ?> DT</span>
                                                    <span class="final-price" id="finalPriceDisplay"><?php echo e(number_format($formation->final_price, 3)); ?> DT</span>
                                                </div>
                                                <input type="hidden" id="final_price" name="final_price" value="<?php echo e(old('final_price', $formation->final_price ?? $formation->price)); ?>">
                                            </div>
                                        </div>

                                        <!-- Catégorie -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Catégorie <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <select class="form-select select2-categorie" id="categorie_id" name="category_id" required>
                                                        <option value="" disabled>Choisir une catégorie</option>
                                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($categorie->id); ?>" <?php echo e(old('categorie_id', $formation->category_id) == $categorie->id ? 'selected' : ''); ?>>
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
                                                            <option value="" disabled>Choisir un professeur</option>
                                                            <?php $__currentLoopData = $professeurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $professeur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($professeur->id); ?>" 
                                                                    <?php echo e($formation->user_id == $professeur->id ? 'selected' : ''); ?>>
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
                                                           value="<?php echo e(old('total_seats', $formation->total_seats)); ?>" 
                                                           required />
                                                </div>
                                                <small class="text-muted">Nombre maximum de participants</small>
                                                <div class="invalid-feedback">Veuillez entrer un nombre de places valide (minimum 1).</div>
                                            </div>
                                        </div>

                                        <!-- Image -->
                                        <div class="mb-3 row">
                                            <label class="col-sm-2 col-form-label">Image <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <?php if($formation->image): ?>
                                                    <div id="currentImageContainer" class="image-container">
                                                        <img src="<?php echo e(asset('storage/' . $formation->image)); ?>?v=<?php echo e(time()); ?>" alt="image" class="centered-image" id="currentImage" />
                                                        <div class="image-actions">
                                                            <button type="button" class="btn" id="deleteImage">
                                                                <i class="fa fa-trash trash-icon" title="Supprimer l'image"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div id="newImagePreview" class="image-preview-container" style="display: none;">
                                                    <img id="previewImage" src="#" alt="Prévisualisation de la nouvelle image" class="image-preview" />
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="imageIcon" style="<?php echo e($formation->image ? 'display: none;' : ''); ?>">
                                                        <i class="fa fa-image"></i>
                                                    </span>
                                                    <input class="form-control" type="file" id="imageUpload" name="image" accept="image/*" style="<?php echo e($formation->image ? 'display: none;' : ''); ?>">
                                                </div>
                                                <small class="text-muted d-block text-center my-2">Formats acceptés: JPG, PNG, GIF. Taille max: 2Mo</small>
                                                <button id="restoreImage" type="button" class="btn" style="display: none;">
                                                    <i class="fa fa-undo"></i> Revenir à l'image actuelle
                                                </button>
                                                <input type="hidden" name="delete_image" id="deleteImageInput" value="0">
                                            </div>
                                        </div>
                                        
                                        <!-- Publication Section -->
                                        <div class="mb-3 row">
                                            <div class="col-12">
                                                <?php if($formation->status): ?>
                                                    <div class="publication-status text-success text-center">
                                                        <i class="fa fa-check-circle"></i> Formation publiée
                                                        <?php if($formation->publish_date): ?>
                                                            le <?php echo e(\Carbon\Carbon::parse($formation->publish_date)->format('d/m/Y H:i')); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                <?php elseif($formation->publish_date): ?>
                                                    <div class="publication-status text-muted text-center">
                                                        <i class="fa fa-clock"></i> Publication programmée pour le <?php echo e(\Carbon\Carbon::parse($formation->publish_date)->format('d/m/Y H:i')); ?>

                                                    </div>
                                                <?php endif; ?>

                                                <div class="d-flex justify-content-center mt-3">
                                                    <div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
                                                        <div class="radio radio-primary mx-2">
                                                            <input id="publishNow" type="radio" name="publication_type" value="now" 
                                                                <?php echo e((old('publication_type', $formation->status ? 'now' : ($formation->publish_date ? 'later' : 'now'))) == 'now' ? 'checked' : ''); ?>>
                                                            <label class="mb-0" for="publishNow">
                                                                <?php echo e($formation->status ? 'Maintenir publiée' : 'Publier immédiatement'); ?>

                                                            </label>
                                                        </div>
                                                        <div class="radio radio-primary mx-2">
                                                            <input id="publishLater" type="radio" name="publication_type" value="later" 
                                                                <?php echo e((old('publication_type', $formation->status ? 'now' : ($formation->publish_date ? 'later' : 'now'))) == 'later' ? 'checked' : ''); ?>>
                                                            <label class="mb-0" for="publishLater">
                                                                <?php echo e($formation->status ? 'Dépublier' : 'Programmer la publication'); ?>

                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Publication Date Container -->
                                                <div id="publishDateContainer" class="mt-3 text-center" 
                                                    style="<?php echo e((old('publication_type', $formation->status ? 'now' : ($formation->publish_date ? 'later' : 'now'))) == 'later' ? 'display: block;' : 'display: none;'); ?>">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="input-group" style="max-width:500px;">
                                                            <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                                            <input class="form-control" 
                                                                type="datetime-local" 
                                                                id="publish_date" 
                                                                name="publish_date" 
                                                                value="<?php echo e(old('publish_date', $formation->publish_date ? \Carbon\Carbon::parse($formation->publish_date)->format('Y-m-d\TH:i') : \Carbon\Carbon::now()->format('Y-m-d\TH:i'))); ?>"
                                                                min="<?php echo e(\Carbon\Carbon::now()->format('Y-m-d\TH:i')); ?>">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Sélectionnez la date et l'heure de publication</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Boutons de soumission -->
                                        <div class="row">
                                            <div class="col">
                                                <div class="text-end mt-4">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa fa-save"></i> Enregistrer
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
    <script src="<?php echo e(asset('assets/js/dropzone/dropzone-script.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/select2-init/single-select.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/form-validation/form-validation.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/formations/formation-edit.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/formations/formation-edit-price.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/tinymce/js/tinymce/tinymce.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/description/description.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/toast/toast.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/calendar/edit-calendar.js')); ?>"></script>


    <script src="https://cdn.tiny.cloud/1/cwjxs6s7k08kvxb3t6udodzrwpomhxtehiozsu4fem2igekf/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        // Script pour afficher le toast si un message de succès est présent
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                toast.success('<?php echo e(session('success')); ?>');
            <?php endif; ?>
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/admin/apps/formation/formationedit.blade.php ENDPATH**/ ?>