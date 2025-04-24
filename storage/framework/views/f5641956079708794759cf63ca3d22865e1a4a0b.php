
<div class="modal fade" id="formation-modal-<?php echo e($formation->id); ?>">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e($formation->title); ?></h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="product-box row">
                    <div class="product-img col-lg-6">
                        <img class="img-fluid" src="<?php echo e(asset('storage/' . $formation->image)); ?>" alt="<?php echo e($formation->title); ?>" />
                    </div>
                    <div class="product-details col-lg-6 text-start">
                        <a href="<?php echo e(route('formationshow', $formation->id)); ?>"> 
                            <h4><?php echo e($formation->title); ?></h4>
                        </a>
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
                        <div class="product-view">
                            <p class="mb-0"><?php echo e($formation->description); ?></p>
                            <div class="mt-3">
                                <p><strong>Places:</strong> <?php echo e($formation->total_seats); ?></p>
                                <p><strong>Durée:</strong> <?php echo e($formation->duration); ?></p>
                                <p><strong>Date début:</strong> <?php echo e(\Carbon\Carbon::parse($formation->start_date)->format('d/m/Y')); ?></p>
                                <p><strong>Date fin:</strong> <?php echo e(\Carbon\Carbon::parse($formation->end_date)->format('d/m/Y')); ?></p>
                                <p><strong>Nombre de cours:</strong> <?php echo e($formation->courses->count()); ?></p>
                            </div>
                        </div>
                        <div class="addcart-btn">
                            <a class="btn btn-primary" href="/panier">Ajouter au panier</a>
                            <a class="btn btn-primary" href="<?php echo e(route('formationshow', $formation->id)); ?>">Voir détails</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo e(asset('assets/js/MonJs/formations-modal.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/MonJs/cart.js')); ?>"></script>

<?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/admin/apps/formation/formation-modal.blade.php ENDPATH**/ ?>