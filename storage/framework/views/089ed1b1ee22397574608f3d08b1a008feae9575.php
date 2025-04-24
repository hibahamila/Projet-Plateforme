<?php $__env->startSection('title'); ?> Ajouter un Feedback <?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .star-rating {
            display: flex;
            flex-direction: row-reverse; 
            justify-content: flex-end;
            gap: 0;
        }
        .star-container {
            position: relative;
            width: 32px;
            height: 32px;
            cursor: pointer;
        }
        .star-rating .fa {
            font-size: 2rem;
            color: #ccc; 
            position: absolute;
            top: 0;
            left: 0;
        }
        .star-rating .fa.fa-star-half-o,
        .star-rating .fa.fa-star {
            color: black; 
        }
        .star-half-left, .star-half-right {
            position: absolute;
            width: 16px;
            height: 32px;
            top: 0;
            z-index: 10;
        }
        .star-half-left { left: 0; }
        .star-half-right { right: 0; }
        .current-rating {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('breadcrumb_title'); ?>
            <h3>Ajouter un Feedback</h3>
        <?php $__env->endSlot(); ?>
        <li class="breadcrumb-item">Feedback</li>
        <li class="breadcrumb-item active">Ajouter</li>
    <?php echo $__env->renderComponent(); ?>

    <div class="container">
        <h2>Ajouter un Feedback</h2>
        
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo e(route('feedbackstore')); ?>" method="POST" class="form theme-form needs-validation" novalidate>
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="formation_id">Formation</label>
                <select name="formation_id" id="formation_id" class="form-control" required>
                    <option value="">-- Sélectionnez une formation --</option>
                    <?php $__currentLoopData = $formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($formation->id); ?>" <?php echo e(old('formation_id') == $formation->id ? 'selected' : ''); ?>>
                            <?php echo e($formation->titre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="invalid-feedback">Veuillez sélectionner une formation.</div>
            </div>

            <div class="form-group mt-3">
                <label for="nombre_rate">Note</label>
                <div class="star-rating">
                    <div class="star-container" data-value="5">
                        <div class="star-half-right" data-value="5.0"></div>
                        <div class="star-half-left" data-value="4.5"></div>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <div class="star-container" data-value="4">
                        <div class="star-half-right" data-value="4.0"></div>
                        <div class="star-half-left" data-value="3.5"></div>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <div class="star-container" data-value="3">
                        <div class="star-half-right" data-value="3.0"></div>
                        <div class="star-half-left" data-value="2.5"></div>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <div class="star-container" data-value="2">
                        <div class="star-half-right" data-value="2.0"></div>
                        <div class="star-half-left" data-value="1.5"></div>
                        <i class="fa fa-star-o"></i>
                    </div>
                    <div class="star-container" data-value="1">
                        <div class="star-half-right" data-value="1.0"></div>
                        <div class="star-half-left" data-value="0.5"></div>
                        <i class="fa fa-star-o"></i>
                    </div>
                </div>
                <div class="current-rating">Note: <span id="rating-value"></span>/5</div>
                <input type="hidden" name="nombre_rate" id="nombre_rate" >
            </div>

            <button type="submit" class="btn btn-success mt-3">Soumettre</button>
            <a href="<?php echo e(route('feedbacks')); ?>" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/feedback/feedback.js')); ?>"></script>
<?php $__env->stopPush(); ?> 
  


<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/feedback/feedbackcreate.blade.php ENDPATH**/ ?>