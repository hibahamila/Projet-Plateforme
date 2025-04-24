

<?php $__env->startSection('title'); ?>Wishlist
 <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/datatables.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
	<?php $__env->startComponent('components.breadcrumb'); ?>
		<?php $__env->slot('breadcrumb_title'); ?>
			<h3>Wishlist</h3>
		<?php $__env->endSlot(); ?>
		<li class="breadcrumb-item">Pages</li>
		<li class="breadcrumb-item">Ecommerce</li>
		<li class="breadcrumb-item active">Wishlist</li>
	<?php echo $__env->renderComponent(); ?>
	
	<div class="container-fluid">
	    <di`v class="row">
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-header pb-0">
	                    <h5>Wishlist</h5>
	                </div>
	                <div class="card-body whishlist-main">
	                    <div class="row">
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/01.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/02.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/03.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart </a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/04.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/05.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/06.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/05.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/07.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/08.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/01.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/02.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-xl-2 col-md-4 col-sm-6">
	                            <div class="prooduct-details-box">
	                                <div class="media">
	                                    <a href="#"><img class="align-self-center img-fluid" src="<?php echo e(asset('assets/images/ecommerce/03.jpg')); ?>" alt="#" /></a>
	                                    <div class="media-body">
	                                        <div class="product-name">
	                                            <a href="#"> <h6>Fancy Women's Cotton</h6></a>
	                                        </div>
	                                        <ul class="rating">
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                            <li><i class="fa fa-star"></i></li>
	                                        </ul>
	                                        <div class="price">Price<span>: 210$</span></div>
	                                        <div class="avaiabilty">
	                                            <div class="text-success">In stock</div>
	                                        </div>
	                                        <a class="btn btn-primary btn-xs" href="#">Move to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	
	<?php $__env->startPush('scripts'); ?>
	<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
	<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/ecommerce/list-wish.blade.php ENDPATH**/ ?>