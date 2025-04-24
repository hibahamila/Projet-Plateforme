

<?php $__env->startSection('title'); ?>Timeline 2
 <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
	<?php $__env->startComponent('components.breadcrumb'); ?>
		<?php $__env->slot('breadcrumb_title'); ?>
			<h3>Timeline 2</h3>
		<?php $__env->endSlot(); ?>
		<li class="breadcrumb-item">Timeline</li>
		<li class="breadcrumb-item active">Timeline 2</li>
	<?php echo $__env->renderComponent(); ?>

	<div class="container-fluid">
		<div class="row">
		  <div class="col-sm-12">
			<div class="card">
			  <div class="card-header">
				<h5>Example</h5>
			  </div>
			  <div class="card-body">
				<div id="timeline-2">
				  <div data-year="2010">Start</div>
				  <div class="active" data-year="2011">Lorem is simply dummy text of the printing and typesetting industry. the printing and typesetting industry.</div>
				  <div data-year="2013">Lorem is simply dummy text of the printing and typesetting industry. </div>
				  <div data-year="2014">Lorem is simply dummy text of the printing and typesetting industry.</div>
				  <div data-year="2015">Lorem is simply dummy text of the printing and typesetting industry.</div>
				  <div data-year="2017">Lorem is simply dummy text of the printing and typesetting industry.</div>
				  <div data-year="2018">End.</div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	</div>

    <?php $__env->startPush('scripts'); ?> 
        <script src="<?php echo e(asset('assets/js/timeline/timeline-v-2/jquery.timeliny.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/js/timeline/timeline-v-2/timeline-v-2-custom.js')); ?>"></script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/bonus-ui/timeline-v-2.blade.php ENDPATH**/ ?>