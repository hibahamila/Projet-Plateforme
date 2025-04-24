

<?php $__env->startSection('title'); ?>Dropzone
 <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/dropzone.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
	<?php $__env->startComponent('components.breadcrumb'); ?>
		<?php $__env->slot('breadcrumb_title'); ?>
			<h3>Dropzone</h3>
		<?php $__env->endSlot(); ?>
		<li class="breadcrumb-item">Advance</li>
		<li class="breadcrumb-item active">Dropzone</li>
    <?php echo $__env->renderComponent(); ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Single File Upload</h5>
                    </div>
                    <div class="card-body">
                        <form class="dropzone digits" id="singleFileUpload" action="/upload.php">
                            <div class="dz-message needsclick">
                                <i class="icon-cloud-up"></i>
                                <h6>Drop files here or click to upload.</h6>
                                <span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Multi File Upload</h5>
                    </div>
                    <div class="card-body">
                        <form class="dropzone dropzone-primary" id="multiFileUpload" action="/upload.php">
                            <div class="dz-message needsclick">
                                <i class="icon-cloud-up"></i>
                                <h6>Drop files here or click to upload.</h6>
                                <span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>File Type Validation</h5>
                    </div>
                    <div class="card-body">
                        <form class="dropzone dropzone-info" id="fileTypeValidation" action="/upload.php">
                            <div class="dz-message needsclick">
                                <i class="icon-cloud-up"></i>
                                <h6>Drop files here or click to upload.</h6>
                                <span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
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
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/bonus-ui/dropzone.blade.php ENDPATH**/ ?>