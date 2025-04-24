 

 <?php $__env->startSection('title'); ?> Ajouter une Question <?php $__env->stopSection(); ?>
 
 <?php $__env->startPush('css'); ?>
     <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/dropzone.css')); ?>">
     <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
     <style>
         #success-message, #delete-message {
             opacity: 0;
             transition: opacity 0.3s ease;
         }
         .alert-success {
             background-color: #d4edda;
             border-color: #c3e6cb;
             color: #155724;
         }
         .alert-danger {
             background-color: #f8d7da;
             border-color: #f5c6cb;
             color: #721c24;
         }
         .custom-btn {
             background-color: #2b786a;
             color: white;
             border-color: #2b786a;
         }
         .custom-btn:hover {
             background-color: #1f5c4d;
             border-color: #1f5c4d;
             color: white;
         }
     </style>
 <?php $__env->stopPush(); ?>
 
 <?php $__env->startSection('content'); ?>
     <?php $__env->startComponent('components.breadcrumb'); ?>
         <?php $__env->slot('breadcrumb_title'); ?>
             <h3>Ajouter une Question</h3>
         <?php $__env->endSlot(); ?>
         <li class="breadcrumb-item">Questions</li>
         <li class="breadcrumb-item active">Ajouter</li>
     <?php echo $__env->renderComponent(); ?>
 
     <div class="container-fluid">
         <div class="row">
             <div class="col-sm-12">
                 <div class="card">
                     <div class="card-body">
                         <?php if($errors->any()): ?>
                             <div class="alert alert-danger">
                                 <ul>
                                     <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                         <li><?php echo e($error); ?></li>
                                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                 </ul>
                             </div>
                         <?php endif; ?>
 
                         <form action="<?php echo e(route('questionstore')); ?>" method="POST" class="needs-validation" novalidate>
                             <?php echo csrf_field(); ?>
 
                             <div class="row">
                                 <div class="col">
                                     <div class="mb-3">
                                         <label class="form-label" for="enonce">Enoncé</label>
                                         <input class="form-control" id="enonce" type="text" name="enonce" placeholder="Enoncé" value="<?php echo e(old('enonce')); ?>" required />
                                         <div class="invalid-feedback">Veuillez entrer un énoncé valide.</div>
                                     </div>
                                 </div>
                             </div>
 
                             <div class="row">
                                 <div class="col">
                                     <div class="mb-3">
                                         <label class="form-label" for="quiz_id">Quiz</label>
                              
                                         <select class="form-select select2-quiz"  name="quiz_id" required>
                                             <option value="" selected disabled>Sélectionnez un quiz</option>
                                             <?php $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                 <option value="<?php echo e($quiz->id); ?>" <?php echo e(old('quiz_id') == $quiz->id ? 'selected' : ''); ?>><?php echo e($quiz->titre); ?></option>
                                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                         </select>
                                         <div class="invalid-feedback">Veuillez sélectionner un quiz valide.</div> 
                                     </div>
                                 </div>
                             </div>
 
                             <div class="row">
                                 <div class="col text-end">
                                     <button class="btn btn-secondary me-3" type="submit">Ajouter</button>
                                     <a href="<?php echo e(route('questions')); ?>" class="btn btn-danger px-4">Annuler</a>
                                 </div>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 
 <?php $__env->startPush('scripts'); ?>
     <script src="<?php echo e(asset('assets/js/dropzone/dropzone.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/js/dropzone/dropzone-script.js')); ?>"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
     <script src="<?php echo e(asset('assets/js/select2-init/single-select.js')); ?>"></script>
 
  <script src="<?php echo e(asset('assets/js/form-validation/form-validation.js')); ?>"></script>


 <?php $__env->stopPush(); ?>
 
 <?php $__env->stopSection(); ?>
 
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/question/questioncreate.blade.php ENDPATH**/ ?>