

<?php $__env->startSection('title'); ?>Tree View
 <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/tree.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
	<?php $__env->startComponent('components.breadcrumb'); ?>
		<?php $__env->slot('breadcrumb_title'); ?>
			<h3>Tree View</h3>
		<?php $__env->endSlot(); ?>
		<li class="breadcrumb-item">Advance</li>
        <li class="breadcrumb-item active">Tree View</li>
    <?php echo $__env->renderComponent(); ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Basic Tree</h5>
                    </div>
                    <div class="card-body">
                        <div id="treeBasic">
                            <ul>
                                <li>
                                    Admin
                                    <ul>
                                        <li data-jstree='{"opened":true}'>
                                            Assets
                                            <ul>
                                                <li data-jstree='{"opened":false}'>
                                                    Css
                                                    <ul>
                                                        <li data-jstree='{"selected":false,"type":"file"}'>Css one</li>
                                                        <li data-jstree='{"type":"file"}'>Css two</li>
                                                    </ul>
                                                </li>
                                                <li data-jstree='{"opened":true}'>
                                                    Js
                                                    <ul>
                                                        <li data-jstree='{"selected":true,"type":"file"}'>Js one</li>
                                                        <li data-jstree='{"type":"file"}'>Js two</li>
                                                    </ul>
                                                </li>
                                                <li data-jstree='{"opened":true}'>
                                                    Scss
                                                    <ul>
                                                        <li data-jstree='{"opened":false}'>
                                                            Sub Child
                                                            <ul>
                                                                <li data-jstree='{"selected":false,"type":"file"}'>Sub File</li>
                                                                <li data-jstree='{"type":"file"}'>Sub File</li>
                                                            </ul>
                                                        </li>
                                                        <li data-jstree='{"type":"file"}'>Scss two</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li data-jstree='{"opened":true}'>
                                            Default
                                            <ul>
                                                <li data-jstree='{"type":"file"}'>Dashboard</li>
                                                <li data-jstree='{"type":"file"}'>Typography</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li data-jstree='{"type":"file"}'>index file</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Checkbox Tree</h5>
                    </div>
                    <div class="card-body">
                        <div id="treecheckbox">
                            <ul>
                                <li>
                                    Admin
                                    <ul>
                                        <li data-jstree='{"opened":true}'>
                                            Assets
                                            <ul>
                                                <li data-jstree='{"opened":false}'>
                                                    Css
                                                    <ul>
                                                        <li data-jstree='{"selected":false,"type":"file"}'>Css one</li>
                                                        <li data-jstree='{"type":"file"}'>Css two</li>
                                                    </ul>
                                                </li>
                                                <li data-jstree='{"opened":true}'>
                                                    Js
                                                    <ul>
                                                        <li data-jstree='{"selected":true,"type":"file"}'>Js one</li>
                                                        <li data-jstree='{"type":"file"}'>Js two</li>
                                                    </ul>
                                                </li>
                                                <li data-jstree='{"opened":true}'>
                                                    Scss
                                                    <ul>
                                                        <li data-jstree='{"opened":false}'>
                                                            Sub Child
                                                            <ul>
                                                                <li data-jstree='{"selected":false,"type":"file"}'>Sub File</li>
                                                                <li data-jstree='{"type":"file"}'>Sub File</li>
                                                            </ul>
                                                        </li>
                                                        <li data-jstree='{"type":"file"}'>Scss two</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li data-jstree='{"opened":true}'>
                                            Default
                                            <ul>
                                                <li data-jstree='{"type":"file"}'>Dashboard</li>
                                                <li data-jstree='{"type":"file"}'>Typography</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li data-jstree='{"type":"file"}'>index file</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Drag Tree</h5>
                    </div>
                    <div class="card-body">
                        <div id="dragTree">
                            <ul>
                                <li>
                                    Admin
                                    <ul>
                                        <li data-jstree='{"opened":true}'>
                                            Assets
                                            <ul>
                                                <li data-jstree='{"opened":false}'>
                                                    Css
                                                    <ul>
                                                        <li data-jstree='{"selected":false,"type":"file"}'>Css one</li>
                                                        <li data-jstree='{"type":"file"}'>Css two</li>
                                                    </ul>
                                                </li>
                                                <li data-jstree='{"opened":true}'>
                                                    Js
                                                    <ul>
                                                        <li data-jstree='{"selected":true,"type":"file"}'>Js one</li>
                                                        <li data-jstree='{"type":"file"}'>Js two</li>
                                                    </ul>
                                                </li>
                                                <li data-jstree='{"opened":true}'>
                                                    Scss
                                                    <ul>
                                                        <li data-jstree='{"opened":false}'>
                                                            Sub Child
                                                            <ul>
                                                                <li data-jstree='{"selected":false,"type":"file"}'>Sub File</li>
                                                                <li data-jstree='{"type":"file"}'>Sub File</li>
                                                            </ul>
                                                        </li>
                                                        <li data-jstree='{"type":"file"}'>Scss two</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li data-jstree='{"opened":true}'>
                                            Default
                                            <ul>
                                                <li data-jstree='{"type":"file"}'>Dashboard</li>
                                                <li data-jstree='{"type":"file"}'>Typography</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li data-jstree='{"type":"file"}'>index file</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Contextmenu Tree</h5>
                    </div>
                    <div class="card-body">
                        <div id="contextmenu">
                            <ul>
                                <li>
                                    Admin
                                    <ul>
                                        <li data-jstree='{"opened":true}'>
                                            Assets
                                            <ul>
                                                <li data-jstree='{"opened":false}'>
                                                    Css
                                                    <ul>
                                                        <li data-jstree='{"selected":false,"type":"file"}'>Css one</li>
                                                        <li data-jstree='{"type":"file"}'>Css two</li>
                                                    </ul>
                                                </li>
                                                <li data-jstree='{"opened":true}'>
                                                    Js
                                                    <ul>
                                                        <li data-jstree='{"selected":true,"type":"file"}'>Js one</li>
                                                        <li data-jstree='{"type":"file"}'>Js two</li>
                                                    </ul>
                                                </li>
                                                <li data-jstree='{"opened":true}'>
                                                    Scss
                                                    <ul>
                                                        <li data-jstree='{"opened":false}'>
                                                            Sub Child
                                                            <ul>
                                                                <li data-jstree='{"selected":false,"type":"file"}'>Sub File</li>
                                                                <li data-jstree='{"type":"file"}'>Sub File</li>
                                                            </ul>
                                                        </li>
                                                        <li data-jstree='{"type":"file"}'>Scss two</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li data-jstree='{"opened":true}'>
                                            Default
                                            <ul>
                                                <li data-jstree='{"type":"file"}'>Dashboard</li>
                                                <li data-jstree='{"type":"file"}'>Typography</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li data-jstree='{"type":"file"}'>index file</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/tree/jstree.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/tree/tree.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/bonus-ui/tree.blade.php ENDPATH**/ ?>