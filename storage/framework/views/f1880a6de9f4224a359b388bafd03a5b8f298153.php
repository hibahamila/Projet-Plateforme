<?php $__env->startSection('title'); ?>
    Liste des Formations <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/prism.css')); ?>">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/formations-gallery.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/cart-core.css')); ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/formation-detail-interaction.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/MonCss/formations-details.css')); ?>">

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <?php echo $__env->make('admin.apps.categorie.categories-filter', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="row project-cards">
            <div class="col-md-12 project-list">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6 p-0">
                            <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="top-home-tab" data-bs-toggle="tab" href="#top-home" role="tab" aria-controls="top-home" aria-selected="true"><i data-feather="target"></i>Tous</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-top-tab" data-bs-toggle="tab" href="#top-contact" role="tab" aria-controls="top-contact" aria-selected="false"><i data-feather="check-circle"></i>Publiées</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-top-tab" data-bs-toggle="tab" href="#top-profile" role="tab" aria-controls="top-profile" aria-selected="false"><i data-feather="info"></i>Non publiées</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 p-0">
                            <a class="btn btn-primary custom-btn" href="<?php echo e(route('formationcreate')); ?>">
                                <i data-feather="plus-square"></i>Ajouter une formation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        

                        

                        <?php if(session('create')): ?>
                            <div class="alert alert-info" id="create-message">
                                <?php echo e(session('create')); ?>

                            </div>
                        <?php endif; ?>

                        <div class="tab-content" id="top-tabContent">
                            <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">


                            <!-- Toutes les formations -->
                            <div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                                <div class="carousel-container">
                                    <div class="formations-carousel">
                                        <?php $__currentLoopData = $formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div>
                                                <div class="formation-card" >

                                                    <?php if($formation->status && isset($formation->is_bestseller) && $formation->is_bestseller): ?>
                                                        <span class="badge-bestseller">Meilleure vente</span>
                                                    <?php endif; ?>

                                                    <?php if($formation->image): ?>
                                                        <img src="<?php echo e(asset('storage/' . $formation->image)); ?>" alt="<?php echo e($formation->title); ?>">


                                                    <?php else: ?>
                                                        <div class="placeholder-image">Image de formation</div>
                                                    <?php endif; ?>

                                                    <h4 class="formation-title"><?php echo e($formation->title); ?></h4>
                                                    <div class="formation-instructor">
                                                        <?php if($formation->user): ?>
                                                            <?php echo e($formation->user->name); ?> <?php echo e($formation->user->lastname ?? ''); ?>

                                                        <?php else: ?>
                                                            Professeur non défini
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="formation-details">
                                                        <div class="formation-duration">
                                                            
                                                            <span class="formation-duration-value" style="display: none;"><?php echo e($formation->duration); ?></span>
                                                        </div>
                                                        <div class="formation-courses-count">
                                                            
                                                            <span class="formation-courses-count-value" style="display: none;"><?php echo e($formation->courses->count()); ?></span> cours
                                                        </div>
                                                    </div>



                                                    <div class="formation-description" style="display: none;"><?php echo $formation->description; ?></div>
                                                            <div class="formation-rating-price">
                                                                <div class="formation-rating">
                                                                    <?php if(isset($formation->average_rating) && $formation->average_rating !== null && ($formation->total_feedbacks ?? 0) > 0): ?>
                                                                        <span class="rating-value"><?php echo e(number_format($formation->average_rating, 1)); ?></span>
                                                                        <span class="rating-stars">
                                                                            <?php
                                                                                $rating = $formation->average_rating;
                                                                                $fullStars = floor($rating);
                                                                                $decimalPart = $rating - $fullStars;
                                                                                $hasHalfStar = $decimalPart >= 0.25; // Seuil à 0.25 pour plus de précision
                                                                            ?>

                                                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                                                <?php if($i <= $fullStars): ?>
                                                                                    <i class="fas fa-star"></i> <!-- Étoile pleine -->
                                                                                <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                                                                                    <i class="fas fa-star-half-alt"></i> <!-- Demi-étoile -->
                                                                                <?php else: ?>
                                                                                    <i class="far fa-star"></i> <!-- Étoile vide -->
                                                                                <?php endif; ?>
                                                                            <?php endfor; ?>
                                                                        </span>
                                                                        <span class="rating-count">(<?php echo e($formation->total_feedbacks); ?>)</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                        <div class="price-container">
                                                            <?php if($formation->discount > 0): ?>
                                                                <div style="display: flex; align-items: center;">
                                                                    <span class="original-price"><?php echo e(number_format($formation->price, 3)); ?> DT</span>
                                                                    <span class="discount-badge">-<?php echo e($formation->discount); ?>%</span>
                                                                </div>
                                                                <span class="final-price"><?php echo e(number_format($formation->final_price, 3)); ?> DT</span>
                                                            <?php else: ?>
                                                                <span class="final-price"><?php echo e(number_format($formation->price, 3)); ?> DT</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <div class="action-icons">
                                                        <i class="icofont icofont-edit edit-icon action-icon" data-edit-url="<?php echo e(route('formationedit', $formation->id)); ?>"></i>
                                                        <i class="icofont icofont-ui-delete delete-icon action-icon" data-delete-url="<?php echo e(route('formationdestroy', $formation->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>"></i>
                                                    </div>
                                                </div>


                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Formations publiées -->
        <div class="tab-pane fade" id="top-contact" role="tabpanel" aria-labelledby="contact-top-tab">
        <div class="carousel-container">
            <div class="formations-carousel-published">
                <?php $__currentLoopData = $formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($formation->status): ?>
                        <div>
                            <div class="formation-card">
                                <?php if(isset($formation->is_bestseller) && $formation->is_bestseller): ?>
                                    <span class="badge-bestseller">Meilleure vente</span>
                                <?php endif; ?>

                                <?php if($formation->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $formation->image)); ?>" alt="<?php echo e($formation->title); ?>">
                                <?php else: ?>
                                    <div class="placeholder-image">Image de formation</div>
                                <?php endif; ?>

                                <h4 class="formation-title"><?php echo e($formation->title); ?></h4>
                                <div class="formation-instructor">
                                    <?php if($formation->user): ?>
                                        <?php echo e($formation->user->name); ?> <?php echo e($formation->user->lastname ?? ''); ?>

                                    <?php else: ?>
                                        Professeur non défini
                                    <?php endif; ?>
                                </div>

                            <div class="formation-description" style="display: none;"><?php echo $formation->description; ?></div>

                            <div class="formation-rating-price">
                                <div class="formation-rating">
                                    <?php if(isset($formation->average_rating) && $formation->average_rating !== null && ($formation->total_feedbacks ?? 0) > 0): ?>
                                        <span class="rating-value"><?php echo e(number_format($formation->average_rating, 1)); ?></span>
                                        <span class="rating-stars">
                                            <?php
                                                $rating = $formation->average_rating;
                                                $fullStars = floor($rating);
                                                $decimalPart = $rating - $fullStars;
                                                $hasHalfStar = $decimalPart >= 0.25; // Seuil à 0.25 pour plus de précision
                                            ?>

                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= $fullStars): ?>
                                                    <i class="fas fa-star"></i> <!-- Étoile pleine -->
                                                <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                                                    <i class="fas fa-star-half-alt"></i> <!-- Demi-étoile -->
                                                <?php else: ?>
                                                    <i class="far fa-star"></i> <!-- Étoile vide -->
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </span>
                                        <span class="rating-count">(<?php echo e($formation->total_feedbacks); ?>)</span>
                                    <?php endif; ?>
                                </div>

                                <div class="price-container">
                                    <?php if($formation->price == 0): ?>
                                        <span class="final-price">Gratuit</span>
                                    <?php else: ?>
                                        <?php if($formation->discount > 0): ?>
                                            <div style="display: flex; align-items: center;">
                                                <span class="original-price"><?php echo e(number_format($formation->price, 3)); ?> DT</span>
                                                <span class="discount-badge">-<?php echo e($formation->discount); ?>%</span>
                                            </div>
                                            <span class="final-price"><?php echo e(number_format($formation->final_price, 3)); ?> DT</span>
                                        <?php else: ?>
                                            <span class="final-price"><?php echo e(number_format($formation->price, 3)); ?> DT</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="action-icons">
                                <i class="icofont icofont-ui-edit edit-icon action-icon" data-edit-url="<?php echo e(route('formationedit', $formation->id)); ?>"></i>
                                <i class="icofont icofont-ui-delete delete-icon action-icon" data-delete-url="<?php echo e(route('formationdestroy', $formation->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>"></i>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
                            <!-- Formations non publiées -->
                                <div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                                    <div class="carousel-container">
                                        <div class="formations-carousel-unpublished">
                                            <?php $__currentLoopData = $formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(!$formation->status): ?>
                                                    <div>
                                                        <div class="formation-card" >
                                                            <?php if($formation->status && isset($formation->is_bestseller) && $formation->is_bestseller): ?>
                                                                <span class="badge-bestseller">Meilleure vente</span>
                                                            <?php endif; ?>

                                                            <?php if($formation->image): ?>
                                                                <img src="<?php echo e(asset('storage/' . $formation->image)); ?>" alt="<?php echo e($formation->title); ?>">
                                                            <?php else: ?>
                                                                <div class="placeholder-image">Image de formation</div>
                                                            <?php endif; ?>

                                                            <h4 class="formation-title"><?php echo e($formation->title); ?></h4>
                                                            <div class="formation-instructor">
                                                                <?php if($formation->user): ?>
                                                                    <?php echo e($formation->user->name); ?> <?php echo e($formation->user->lastname ?? ''); ?>

                                                                <?php else: ?>
                                                                    Professeur non défini
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="formation-description" style="display: none;"><?php echo $formation->description; ?></div>




                                                            <div class="formation-rating-price">
                                                                <div class="formation-rating">
                                                                    <?php if(isset($formation->average_rating) && $formation->average_rating !== null && ($formation->total_feedbacks ?? 0) > 0): ?>
                                                                        <span class="rating-value"><?php echo e(number_format($formation->average_rating, 1)); ?></span>
                                                                        <span class="rating-stars">
                                                                            <?php
                                                                                $rating = $formation->average_rating;
                                                                                $fullStars = floor($rating);
                                                                                $decimalPart = $rating - $fullStars;
                                                                                $hasHalfStar = $decimalPart >= 0.25;
                                                                            ?>

                                                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                                                <?php if($i <= $fullStars): ?>
                                                                                    <i class="fas fa-star"></i>
                                                                                <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                                                                                    <i class="fas fa-star-half-alt"></i>
                                                                                <?php else: ?>
                                                                                    <i class="far fa-star"></i>
                                                                                <?php endif; ?>
                                                                            <?php endfor; ?>
                                                                        </span>
                                                                        <span class="rating-count">(<?php echo e($formation->total_feedbacks); ?>)</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="price-container">
                                                                    <?php if($formation->discount > 0): ?>
                                                                        <div style="display: flex; align-items: center;">
                                                                            <span class="original-price"><?php echo e(number_format($formation->price, 3)); ?> DT</span>
                                                                            <span class="discount-badge">-<?php echo e($formation->discount); ?>%</span>
                                                                        </div>
                                                                        <span class="final-price"><?php echo e(number_format($formation->final_price, 3)); ?> DT</span>
                                                                    <?php else: ?>
                                                                        <span class="final-price"><?php echo e(number_format($formation->price, 3)); ?> DT</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>

                                                            <div class="action-icons">
                                                                <i class="icofont icofont-ui-edit edit-icon action-icon" data-edit-url="<?php echo e(route('formationedit', $formation->id)); ?>"></i>
                                                                <i class="icofont icofont-ui-delete delete-icon action-icon" data-delete-url="<?php echo e(route('formationdestroy', $formation->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>


                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/prism/prism.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/height-equal.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/clipboard/clipboard.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/custom-card/custom-card.js')); ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/formations/utils.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/formations/formation-gallery.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/formations/formation-detail-paneau.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/formations/cart-core.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/formations/cart-ui.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/formations/action-menus.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/formations/categorie.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/MonJs/toast/toast.js')); ?>"></script>

    <script>
        // Script pour afficher le toast si un message de succès est présent
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                toast.success('<?php echo e(session('success')); ?>');
            <?php endif; ?>
        });
    </script>


<style id="cart-badge-styles">
    .cart-badge, .custom-violet-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #2563EB;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        z-index: 10;
    }
</style>
<script>
    // Script prioritaire qui s'exécute immédiatement
    (function() {
        // Récupérer la valeur du panier depuis localStorage
        const count = parseInt(localStorage.getItem('cartCount') || '0');
        if (count <= 0) return; // Ne rien faire s'il n'y a pas d'articles
        
        // Créer l'élément badge
        const badge = document.createElement('span');
        badge.className = 'cart-badge custom-violet-badge badge-preloaded';
        badge.textContent = count.toString();
        badge.style.display = 'block';
        
        // Stocker l'élément dans une variable globale pour référence future
        window.__preloadedBadge = badge;
        
        // Fonction qui tente d'ajouter le badge au conteneur du panier
        function attachBadge() {
            // Liste des sélecteurs possibles pour l'icône du panier
            const selectors = [
                '.shopping-cart-icon', 
                'svg[data-icon="shopping-cart"]', 
                '.cart-icon', 
                'a[href*="panier"] svg', 
                '.cart-container svg',
                'a[href*="panier"]',
                '.cart-link',
                '.header-cart'
            ];
            
            // Essayer de trouver l'icône du panier
            let cartIcon = null;
            for (const selector of selectors) {
                cartIcon = document.querySelector(selector);
                if (cartIcon) break;
            }
            
            if (!cartIcon) {
                // Réessayer rapidement si l'icône n'est pas encore disponible
                setTimeout(attachBadge, 10);
                return;
            }
            
            // Trouver le conteneur de l'icône
            const cartContainer = cartIcon.closest('a, div, button, .cart-container') || cartIcon;
            
            // Vérifier si un badge existe déjà
            if (!cartContainer.querySelector('.cart-badge, .custom-violet-badge')) {
                // Forcer la position relative sur le conteneur
                cartContainer.style.position = 'relative';
                
                // Ajouter le badge
                cartContainer.appendChild(badge);
            }
        }
        
        // Commencer la tentative d'attachement immédiatement
        attachBadge();
        
        // Aussi ajouter un écouteur pour le chargement du DOM complet
        document.addEventListener('DOMContentLoaded', attachBadge);
    })();
</script>








<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/formation/formations.blade.php ENDPATH**/ ?>