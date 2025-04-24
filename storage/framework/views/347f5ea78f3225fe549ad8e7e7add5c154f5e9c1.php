<?php $__env->startSection('title'); ?>Cart
 <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
	<?php $__env->startComponent('components.breadcrumb'); ?>
		<?php $__env->slot('breadcrumb_title'); ?>
			<h3>Cart</h3>
		<?php $__env->endSlot(); ?>
		<li class="breadcrumb-item">Pages</li>
		<li class="breadcrumb-item">Ecommerce</li>
		<li class="breadcrumb-item active">Cart</li>
	<?php echo $__env->renderComponent(); ?>
	
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-header pb-0">
	                    <h5>Cart</h5>
	                </div>
	                <div class="card-body">
	                    <div class="row">
	                        <div class="order-history table-responsive wishlist">
	                            <table class="table table-bordered">
	                                <thead>
	                                    <tr>
	                                        <th>Prdouct</th>
	                                        <th>Prdouct Name</th>
	                                        <th>Price</th>
	                                        <th>Quantity</th>
	                                        <th>Action</th>
	                                        <th>Total</th>
	                                    </tr>
	                                </thead>
	                                <tbody>
	                                    <tr>
	                                        <td><img class="img-fluid img-40" src="<?php echo e(asset('assets/images/product/1.png')); ?>" alt="#" /></td>
	                                        <td>
	                                            <div class="product-name">
	                                                <a href="#"> <h6>Long Top</h6></a>
	                                            </div>
	                                        </td>
	                                        <td>$21</td>
	                                        <td>
	                                            <fieldset class="qty-box">
	                                                <div class="input-group">
	                                                    <input class="touchspin text-center" type="text" value="5" />
	                                                </div>
	                                            </fieldset>
	                                        </td>
	                                        <td><i data-feather="x-circle"></i></td>
	                                        <td>$12456</td>
	                                    </tr>
	                                    <tr>
	                                        <td><img class="img-fluid img-40" src="<?php echo e(asset('assets/images/product/13.png')); ?>" alt="#" /></td>
	                                        <td>
	                                            <div class="product-name">
	                                                <a href="#"> <h6>Fancy watch</h6></a>
	                                            </div>
	                                        </td>
	                                        <td>$50</td>
	                                        <td>
	                                            <fieldset class="qty-box">
	                                                <div class="input-group">
	                                                    <input class="touchspin text-center" type="text" value="5" />
	                                                </div>
	                                            </fieldset>
	                                        </td>
	                                        <td><i data-feather="x-circle"></i></td>
	                                        <td>$12456</td>
	                                    </tr>
	                                    <tr>
	                                        <td><img class="img-fluid img-40" src="<?php echo e(asset('assets/images/product/4.png')); ?>" alt="#" /></td>
	                                        <td>
	                                            <div class="product-name">
	                                                <a href="#"> <h6>Man shoes</h6></a>
	                                            </div>
	                                        </td>
	                                        <td>$11</td>
	                                        <td>
	                                            <fieldset class="qty-box">
	                                                <div class="input-group">
	                                                    <input class="touchspin text-center" type="text" value="5" />
	                                                </div>
	                                            </fieldset>
	                                        </td>
	                                        <td><i data-feather="x-circle"></i></td>
	                                        <td>$12456</td>
	                                    </tr>
	                                    <tr>
	                                        <td colspan="4">
	                                            <div class="input-group"><input class="form-control me-2" type="text" placeholder="Enter coupan code" /><a class="btn btn-primary" href="#">Apply</a></div>
	                                        </td>
	                                        <td class="total-amount">
	                                            <h6 class="m-0 text-end"><span class="f-w-600">Total Price :</span></h6>
	                                        </td>
	                                        <td><span>$6935.00 </span></td>
	                                    </tr>
	                                    <tr>
	                                        <td class="text-end" colspan="5"><a class="btn btn-secondary cart-btn-transform" href="#">continue shopping</a></td>
	                                        <td><a class="btn btn-success cart-btn-transform" href="#">check out</a></td>
	                                    </tr>
	                                </tbody>
	                            </table>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	
	<?php $__env->startPush('scripts'); ?>
	<script src="<?php echo e(asset('assets/js/touchspin/vendors.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/touchspin/touchspin.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/touchspin/input-groups.min.js')); ?>"></script>
	<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/admin/apps/ecommerce/cart.blade.php ENDPATH**/ ?>