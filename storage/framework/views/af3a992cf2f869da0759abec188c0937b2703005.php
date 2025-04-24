<div class="categories-filter">
    <div class="categories-wrapper">
        <button class="nav-button prev-button" style="display: none;">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="categories-slider">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="category-item <?php echo e(request()->get('category_id') == $category->id || (request()->get('categorie_id') === null && $loop->first) ? 'active' : ''); ?>">
                <a href="<?php echo e(route('formations', ['category_id' => $category->id])); ?>" 
                   class="category-link" 
                   data-category-id="<?php echo e($category->id); ?>">
                    <span class="category-title"><?php echo e($category->title); ?></span>
                    <span class="participant-count">+ <?php echo e($category->trainings_count ?? 0); ?> formations</span>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <button class="nav-button next-button">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>


<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/categories-filter.css')); ?>">
<?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/categorie/categories-filter.blade.php ENDPATH**/ ?>