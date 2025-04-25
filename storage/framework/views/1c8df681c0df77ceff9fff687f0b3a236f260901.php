<?php $__env->startSection('title'); ?>Formations
 <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/select2.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/owlcarousel.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/range-slider.css')); ?>">

<style>
    .hover-effect:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
    transition: all 0.3s ease;
}
</style>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid product-wrapper">
    <div class="product-grid">
        <div class="feature-products">
            <div class="row m-b-10">
                <div class="col-md-3 col-sm-4">
                    <div class="d-none-productlist filter-toggle">
                        <h6 class="mb-0">
                            Filters<span class="ms-2"><i class="toggle-data" data-feather="chevron-down"></i></span>
                        </h6>
                    </div>
                </div>
                <div class="col-md-9 col-sm-8 text-end">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="select2-drpdwn-product select-options me-3" style="margin-top: 10px;">
                            <select class="form-control btn-square status-filter" name="status">
                                <option value="">Tous</option>
                                <option value="1" <?php echo e(request()->status == '1' ? 'selected' : ''); ?>>Publiée</option>
                                <option value="0" <?php echo e(request()->status == '0' ? 'selected' : ''); ?>>Non publiée</option>
                            </select>
                        </div>
                        <div class="btn-group">
                            <a href="<?php echo e(route('formationcreate')); ?>" class="btn btn-primary btn-sm d-flex align-items-center">
                                <i data-feather="plus-square" class="me-2"></i> Nouvelle Formation
                            </a>
                        </div>
                    </div>
                </div>  
            </div>

	            <div class="row">
	                <div class="col-md-12">
	                    <div class="pro-filter-sec">
	                        <div class="product-sidebar">
	                            <div class="filter-section">
	                                <div class="card">
	                                    <div class="card-header">
	                                        <h6 class="mb-0 f-w-600">
	                                            Filtres<span class="pull-right"><i class="fa fa-chevron-down toggle-data"></i></span>
	                                        </h6>
	                                    </div>
	                                    <div class="left-filter">
	                                        <div class="card-body filter-cards-view animate-chk">
	                                            <div class="product-filter">
	                                                <h6 class="f-w-600">Catégories</h6>
	                                                <div class="checkbox-animated mt-0">
                                                        <label class="d-block" for="category-all">
                                                            <input class="radio_animated" id="category-all" type="radio" name="category_filter" value="" <?php echo e(!request()->has('category_id') || request()->category_id === null || request()->category_id === '' ? 'checked' : ''); ?>/>
                                                            Toutes les catégories
                                                        </label>
                                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                                    <label class="d-block" for="category-<?php echo e($category->id); ?>">
                                                            <input class="radio_animated" id="category-<?php echo e($category->id); ?>" type="radio" name="category_filter" value="<?php echo e($category->id); ?>" <?php echo e(request()->category_id == $category->id ? 'checked' : ''); ?>/>
                                                            <?php echo e($category->title); ?> (<?php echo e($category->trainings_count); ?>)
                                                        </label>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	                                                </div>
	                                            </div>
	                                           
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="product-search">
	                            <form>
	                                <div class="form-group m-0">
                                        <input class="form-control" type="search" placeholder="Rechercher..." data-original-title="" title="" id="search-formations" />
                                        <i class="fa fa-search"></i>
                                    </div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="product-wrapper-grid">
	            <div class="row formations-container">
                    <?php $__empty_1 = true; $__currentLoopData = $formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

	                <div class="col-xl-3 col-sm-6 xl-4 formation-item">
	                    <div class="card">
	                        <div class="product-box">
	                            <div class="product-img">
                                    <?php if($formation->type == 'gratuite'): ?>
                                    <div class="ribbon ribbon-danger">Gratuite</div>
                                    <?php endif; ?>
                                    <?php if($formation->discount > 0): ?>
                                    <div class="ribbon ribbon-success ribbon-right"><?php echo e($formation->discount); ?>%</div>
                                    <?php endif; ?>
	                                <img class="img-fluid" src="<?php echo e(asset('storage/' . $formation->image)); ?>" alt="<?php echo e($formation->title); ?>" />
	                                <div class="product-hover">
	                                    <ul>
                                            
	                                        <li>
	                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#formation-modal-<?php echo e($formation->id); ?>">
                                                    <i class="icon-eye"></i>
                                                </a>
                                                <li>
                                                    <a href="<?php echo e(route('panier.index')); ?>"><i class="icon-shopping-cart"></i></a>
                                                </li>
                                                
	                                        </li>

                                            <li>
	                                            <a href="<?php echo e(route('formationedit', $formation->id)); ?>"><i class="icon-pencil"></i></a>
	                                        </li>
                                            
                                            <li>
                                                <a href="javascript:void(0)" class="delete-formation" data-id="<?php echo e($formation->id); ?>">
                                                    <i class="icon-trash"></i>
                                                </a>
                                            </li>
	                                    </ul>
	                                </div>
	                            </div>
                                
                                <?php echo $__env->make('admin.apps.formation.formation-modal', ['formation' => $formation], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

	                            <div class="product-details">
	                                <a href="<?php echo e(route('formationshow', $formation->id)); ?>"> 
                                    <h4><?php echo e($formation->title); ?></h4>
                                </a>
	                                <p>Par <?php echo e($formation->user->name); ?> <?php echo e($formation->user->lastname); ?></p>
                                    <div class="mb-2">
                                        <span class="badge badge-light-info"><?php echo e($formation->courses->count()); ?> cours</span>
                                        <span class="badge badge-light-secondary"><?php echo e($formation->total_seats); ?> places</span>
                                    </div>
	                                <div class="product-price">
                                        <?php if($formation->type == 'payante'): ?>
                                            <?php if($formation->discount > 0): ?>
                                            <?php echo e(number_format($formation->final_price, 2)); ?> Dt
                                            <del><?php echo e(number_format($formation->price, 2)); ?> Dt</del>
                                            <?php else: ?>
                                            <?php echo e(number_format($formation->price, 2)); ?> Dt
                                            <?php endif; ?>
                                        <?php else: ?>
                                            &nbsp;
                                        <?php endif; ?>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            Aucune formation disponible. 
                        </div>
                    </div>
                    <?php endif; ?>
	            </div>
	        </div>
	    </div>
	</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cette formation ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <form id="deleteFormationForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

	<?php $__env->startPush('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<script src="<?php echo e(asset('assets/js/range-slider/ion.rangeSlider.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/range-slider/rangeslider-script.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/touchspin/vendors.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/touchspin/touchspin.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/touchspin/input-groups.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/owlcarousel/owl.carousel.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/tooltip-init.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/product-tab.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/formations-modal.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/toast/toast.js')); ?>"></script>


    <script src="<?php echo e(asset('assets/js/MonJs/formations.js')); ?>"></script>

	<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/admin/apps/formation/formations.blade.php ENDPATH**/ ?>