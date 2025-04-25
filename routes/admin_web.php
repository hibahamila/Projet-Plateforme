<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\EditController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ChapitreController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ReponseController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\ReservationController;













Route::get('sign-up', [RegisterController::class, 'showRegistrationForm'])->name('sign-up');
Route::post('sign-up', [RegisterController::class, 'register'])->name('register');
Route::get('validation', [RegisterController::class, 'showValidationForm'])->name('validation.form');
Route::post('validation', [RegisterController::class, 'validateAccount'])->name('validation.code');
Route::get('resend-code', [RegisterController::class, 'resendCode'])->name('resend.code');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('forgot.password.send');

Route::get('/verify-code', [ForgotPasswordController::class, 'showVerifyForm'])->name('reset.password.form');

Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('reset.password.verify');


Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');


Route::post('/reset-password', [ForgotPasswordController::class, 'changePassword'])->name('password.reset.update');
Route::get('/blocked-account', [EditController::class, 'pageDeBlockage'])->name('blocked.account');

Route::group(['middleware' => 'auth'], function () {
	Route::prefix('admin')->group(function () {
	});
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/parametre', [EditController::class, 'index'])->name('parametre');
        Route::get('/edit', [EditController::class, 'edit'])->name('edit');
        Route::put('/update', [EditController::class, 'update'])->name('update');
        Route::get('/updateCompte', [EditController::class, 'updateCompte'])->name('updateCompte');
        Route::get('/updateEmail', [EditController::class, 'updateEmail'])->name('updateEmail');
        Route::post('/check-email', [EditController::class, 'checkEmailAvailability'])->name('checkEmail');
        // Ajoutez cette route avec vos autres routes de profil
        Route::post('/verify-password', [EditController::class, 'verifyPassword'])->name('verifyPassword');
        Route::post('/sendEmailVerificationCode', [EditController::class, 'sendEmailVerificationCode'])->name('sendEmailVerificationCode');
        Route::get('/verifyEmail', [EditController::class, 'validateCode'])->name('validateCode');
        Route::post('/verifyEmail', [EditController::class, 'verifyAndUpdateEmail'])->name('verifyEmail');
        Route::get('/editPassword', [EditController::class, 'editPassword'])->name('editPassword');
        Route::post('/updatePassword', [EditController::class, 'updatePassword'])->name('updatePassword');
    });


	Route::prefix('dashboard')->group(function () {
		Route::view('dashboard-02', 'admin.dashboard.dashboard-02')->name('dashboard-02');
	});

	Route::prefix('widgets')->group(function () {
		Route::view('general-widget', 'admin.widgets.general-widget')->name('general-widget');
		Route::view('chart-widget', 'admin.widgets.chart-widget')->name('chart-widget');
	});

	Route::prefix('ui-kits')->group(function () {
		Route::view('state-color', 'admin.ui-kits.state-color')->name('state-color');
		Route::view('typography', 'admin.ui-kits.typography')->name('typography');
		Route::view('avatars', 'admin.ui-kits.avatars')->name('avatars');
		Route::view('helper-classes', 'admin.ui-kits.helper-classes')->name('helper-classes');
		Route::view('grid', 'admin.ui-kits.grid')->name('grid');
		Route::view('tag-pills', 'admin.ui-kits.tag-pills')->name('tag-pills');
		Route::view('progress-bar', 'admin.ui-kits.progress-bar')->name('progress-bar');
		Route::view('modal', 'admin.ui-kits.modal')->name('modal');
		Route::view('alert', 'admin.ui-kits.alert')->name('alert');
		Route::view('popover', 'admin.ui-kits.popover')->name('popover');
		Route::view('tooltip', 'admin.ui-kits.tooltip')->name('tooltip');
		Route::view('loader', 'admin.ui-kits.loader')->name('loader');
		Route::view('dropdown', 'admin.ui-kits.dropdown')->name('dropdown');
		Route::view('according', 'admin.ui-kits.according')->name('according');
		Route::view('tab-bootstrap', 'admin.ui-kits.tab-bootstrap')->name('tab-bootstrap');
		Route::view('tab-material', 'admin.ui-kits.tab-material')->name('tab-material');
		Route::view('navs', 'admin.ui-kits.navs')->name('navs');
		Route::view('box-shadow', 'admin.ui-kits.box-shadow')->name('box-shadow');
		Route::view('list', 'admin.ui-kits.list')->name('list');
	});



	Route::prefix('bonus-ui')->group( function(){
		Route::view('scrollable', 'admin.bonus-ui.scrollable')->name('scrollable');
		Route::view('tree', 'admin.bonus-ui.tree')->name('tree');
		Route::view('bootstrap-notify', 'admin.bonus-ui.bootstrap-notify')->name('bootstrap-notify');
		Route::view('rating', 'admin.bonus-ui.rating')->name('rating');
		Route::view('dropzone', 'admin.bonus-ui.dropzone')->name('dropzone');
		Route::view('tour', 'admin.bonus-ui.tour')->name('tour');
		Route::view('sweet-alert2', 'admin.bonus-ui.sweet-alert2')->name('sweet-alert2');
		Route::view('modal-animated', 'admin.bonus-ui.modal-animated')->name('modal-animated');
		Route::view('owl-carousel', 'admin.bonus-ui.owl-carousel')->name('owl-carousel');
		Route::view('ribbons', 'admin.bonus-ui.ribbons')->name('ribbons');
		Route::view('pagination', 'admin.bonus-ui.pagination')->name('pagination');
		Route::view('steps', 'admin.bonus-ui.steps')->name('steps');
		Route::view('breadcrumb', 'admin.bonus-ui.breadcrumb')->name('breadcrumb');
		Route::view('range-slider', 'admin.bonus-ui.range-slider')->name('range-slider');
		Route::view('image-cropper', 'admin.bonus-ui.image-cropper')->name('image-cropper');
		Route::view('sticky', 'admin.bonus-ui.sticky')->name('sticky');
		Route::view('basic-card', 'admin.bonus-ui.basic-card')->name('basic-card');
		Route::view('creative-card', 'admin.bonus-ui.creative-card')->name('creative-card');
		Route::view('tabbed-card', 'admin.bonus-ui.tabbed-card')->name('tabbed-card');
		Route::view('dragable-card', 'admin.bonus-ui.dragable-card')->name('dragable-card');
		Route::view('timeline-v-1', 'admin.bonus-ui.timeline-v-1')->name('timeline-v-1');
		Route::view('timeline-v-2', 'admin.bonus-ui.timeline-v-2')->name('timeline-v-2');
	});

	Route::prefix('builders')->group( function(){
		Route::view('form-builder-1', 'admin.builders.form-builder-1')->name('form-builder-1');
		Route::view('form-builder-2', 'admin.builders.form-builder-2')->name('form-builder-2');
		Route::view('pagebuild', 'admin.builders.pagebuild')->name('pagebuild');
		Route::view('button-builder', 'admin.builders.button-builder')->name('button-builder');
	});

	Route::prefix('animation')->group( function(){
		Route::view('animate', 'admin.animation.animate')->name('animate');
		Route::view('scroll-reval', 'admin.animation.scroll-reval')->name('scroll-reval');
		Route::view('aos', 'admin.animation.aos')->name('aos');
		Route::view('tilt', 'admin.animation.tilt')->name('tilt');
		Route::view('wow', 'admin.animation.wow')->name('wow');
	});

	Route::prefix('icons')->group( function(){
		Route::view('flag-icon', 'admin.icons.flag-icon')->name('flag-icon');
		Route::view('font-awesome', 'admin.icons.font-awesome')->name('font-awesome');
		Route::view('ico-icon', 'admin.icons.ico-icon')->name('ico-icon');
		Route::view('themify-icon', 'admin.icons.themify-icon')->name('themify-icon');
		Route::view('feather-icon', 'admin.icons.feather-icon')->name('feather-icon');
		Route::view('whether-icon', 'admin.icons.whether-icon')->name('whether-icon');
	});

	Route::prefix('buttons')->group( function(){
		Route::view('buttons', 'admin.buttons.buttons')->name('buttons');
		Route::view('buttons-flat', 'admin.buttons.buttons-flat')->name('buttons-flat');
		Route::view('buttons-edge', 'admin.buttons.buttons-edge')->name('buttons-edge');
		Route::view('raised-button', 'admin.buttons.raised-button')->name('raised-button');
		Route::view('button-group', 'admin.buttons.button-group')->name('button-group');
	});

	Route::prefix('charts')->group( function(){
		Route::view('chart-apex', 'admin.charts.chart-apex')->name('chart-apex');
		Route::view('chart-google', 'admin.charts.chart-google')->name('chart-google');
		Route::view('chart-sparkline', 'admin.charts.chart-sparkline')->name('chart-sparkline');
		Route::view('chart-flot', 'admin.charts.chart-flot')->name('chart-flot');
		Route::view('chart-knob', 'admin.charts.chart-knob')->name('chart-knob');
		Route::view('chart-morris', 'admin.charts.chart-morris')->name('chart-morris');
		Route::view('chartjs', 'admin.charts.chartjs')->name('chartjs');
		Route::view('chartist', 'admin.charts.chartist')->name('chartist');
		Route::view('chart-peity', 'admin.charts.chart-peity')->name('chart-peity');
	});






	
	Route::view('jsgrid-table', 'admin.tables.jsgrid-table')->name('jsgrid-table');


	Route::view('file-manager', 'admin.apps.file-manager')->name('file-manager');
	Route::view('kanban', 'admin.apps.kanban')->name('kanban');

	Route::prefix('ecommerce')->group( function(){
		Route::view('product', 'admin.apps.ecommerce.product')->name('product');
		Route::view('product-page', 'admin.apps.ecommerce.product-page')->name('product-page');
		Route::view('list-products', 'admin.apps.ecommerce.list-products')->name('list-products');
		Route::view('payment-details', 'admin.apps.ecommerce.payment-details')->name('payment-details');
		Route::view('order-history', 'admin.apps.ecommerce.order-history')->name('order-history');
		Route::view('invoice-template', 'admin.apps.ecommerce.invoice-template')->name('invoice-template');
		Route::view('cart', 'admin.apps.ecommerce.cart')->name('cart');
		Route::view('list-wish', 'admin.apps.ecommerce.list-wish')->name('list-wish');
		Route::view('checkout', 'admin.apps.ecommerce.checkout')->name('checkout');
		Route::view('pricing', 'admin.apps.ecommerce.pricing')->name('pricing');
	});

	Route::prefix('email')->group( function(){
		Route::view('email_inbox', 'admin.apps.email_inbox')->name('email_inbox');
		Route::view('email_read', 'admin.apps.email_read')->name('email_read');
		Route::view('email_compose', 'admin.apps.email_compose')->name('email_compose');
	});

	Route::prefix('chat')->group( function(){
		Route::view('chat', 'admin.apps.chat')->name('chat');
		Route::view('chat-video', 'admin.apps.chat-video')->name('chat-video');
	});

	Route::prefix('users')->group( function(){
		Route::view('user-profile', 'admin.apps.user-profile')->name('user-profile');
		Route::view('edit-profile', 'admin.apps.edit-profile')->name('edit-profile');
		Route::view('user-cards', 'admin.apps.user-cards')->name('user-cards');
	});

	Route::view('bookmark', 'admin.apps.bookmark')->name('bookmark');
	Route::view('users', 'admin.apps.contacts')->name('contacts');
	Route::view('task', 'admin.apps.task')->name('task');
	Route::view('calendar-basic', 'admin.apps.calendar-basic')->name('calendar-basic');
	Route::view('social-app', 'admin.apps.social-app')->name('social-app');
	Route::view('to-do', 'admin.apps.to-do')->name('to-do');
	Route::view('search', 'admin.apps.search')->name('search');

	Route::view('internationalization', 'admin.pages.internationalization')->name('internationalization');

	Route::view('error-page1', 'admin.errors.error-page1')->name('error-page1');
	Route::view('error-page2', 'admin.errors.error-page2')->name('error-page2');
	Route::view('error-page3', 'admin.errors.error-page3')->name('error-page3');
	Route::view('error-page4', 'admin.errors.error-page4')->name('error-page4');

	Route::view('login', 'admin.authentication.login')->name('login');
	Route::view('login_one', 'admin.authentication.login_one')->name('login_one');
	Route::view('login_two', 'admin.authentication.login_two')->name('login_two');
	Route::view('login-bs-validation', 'admin.authentication.login-bs-validation')->name('login-bs-validation');
	Route::view('login-bs-tt-validation', 'admin.authentication.login-bs-tt-validation')->name('login-bs-tt-validation');
	Route::view('login-sa-validation', 'admin.authentication.login-sa-validation')->name('login-sa-validation');

	Route::view('sign-up-one', 'admin.authentication.sign-up-one')->name('sign-up-one');
	Route::view('sign-up-two', 'admin.authentication.sign-up-two')->name('sign-up-two');
	Route::view('unlock', 'admin.authentication.unlock')->name('unlock');
	// Route::view('forget-password', 'admin.authentication.forget-password')->name('forget-password');

	Route::view('maintenance', 'admin.authentication.maintenance')->name('maintenance');

	Route::view('comingsoon', 'admin.comingsoon.comingsoon')->name('comingsoon');
	Route::view('comingsoon-bg-video', 'admin.comingsoon.comingsoon-bg-video')->name('comingsoon-bg-video');
	Route::view('comingsoon-bg-img', 'admin.comingsoon.comingsoon-bg-img')->name('comingsoon-bg-img');

	Route::view('basic-template', 'admin.email.basic-template')->name('basic-template');
	Route::view('email-header', 'admin.email.email-header')->name('email-header');
	Route::view('template-email', 'admin.email.template-email')->name('template-email');
	Route::view('template-email-2', 'admin.email.template-email-2')->name('template-email-2');
	Route::view('ecommerce-templates', 'admin.email.ecommerce-templates')->name('ecommerce-templates');
	Route::view('email-order-success', 'admin.email.email-order-success')->name('email-order-success');

	Route::prefix('gallery')->group( function(){
		Route::view('/', 'admin.gallery.gallery')->name('gallery');
		Route::view('gallery-masonry', 'admin.gallery.gallery-masonry')->name('gallery-masonry');
		Route::view('gallery-with-description', 'admin.gallery.gallery-with-description')->name('gallery-with-description');
		Route::view('masonry-gallery-with-disc', 'admin.gallery.masonry-gallery-with-disc')->name('masonry-gallery-with-disc');
		Route::view('gallery-hover', 'admin.gallery.gallery-hover')->name('gallery-hover');
	});

	Route::prefix('blog')->group( function(){
		Route::view('/', 'admin.miscellaneous.blog')->name('blog');
		Route::view('blog-single', 'admin.miscellaneous.blog-single')->name('blog-single');
		Route::view('add-post', 'admin.miscellaneous.add-post')->name('add-post');
	});

	Route::view('faq', 'admin.miscellaneous.faq')->name('faq');

	Route::prefix('job-search')->group( function(){
		Route::view('job-cards-view', 'admin.miscellaneous.job-cards-view')->name('job-cards-view');
		Route::view('job-list-view', 'admin.miscellaneous.job-list-view')->name('job-list-view');
		Route::view('job-details', 'admin.miscellaneous.job-details')->name('job-details');
		Route::view('job-apply', 'admin.miscellaneous.job-apply')->name('job-apply');
	});

	Route::prefix('learning')->group( function(){
		Route::view('learning-list-view', 'admin.miscellaneous.learning-list-view')->name('learning-list-view');
		Route::view('learning-detailed', 'admin.miscellaneous.learning-detailed')->name('learning-detailed');
	});

	Route::prefix('maps')->group( function(){
		Route::view('map-js', 'admin.miscellaneous.map-js')->name('map-js');
		Route::view('vector-map', 'admin.miscellaneous.vector-map')->name('vector-map');
	});

	Route::prefix('editors')->group( function(){
		Route::view('summernote', 'admin.miscellaneous.summernote')->name('summernote');
		Route::view('ckeditor', 'admin.miscellaneous.ckeditor')->name('ckeditor');
		Route::view('simple-MDE', 'admin.miscellaneous.simple-MDE')->name('simple-MDE');
		Route::view('ace-code-editor', 'admin.miscellaneous.ace-code-editor')->name('ace-code-editor');
	});

	Route::view('knowledgebase', 'admin.miscellaneous.knowledgebase')->name('knowledgebase');



    Route::prefix('categorie')->group(function () {
		Route::get('categories', [CategorieController::class, 'index'])->name('categories'); // Liste
		Route::get('categoriecreate', [CategorieController::class, 'create'])->name('categoriecreate'); // Formulaire de création
		Route::post('store', [CategorieController::class, 'store'])->name('categoriestore'); // Enregistrement d'une nouvelle catégorie
		Route::get('/{id}', [CategorieController::class, 'show'])->name('categorieshow'); // Détails d'une catégorie
		Route::get('/{id}/edit', [CategorieController::class, 'edit'])->name('categorieedit'); // Formulaire de modification
		Route::put('/{id}', [CategorieController::class, 'update'])->name('categorieupdate'); // Mise à jour d'une catégorie
		Route::delete('/{id}', [CategorieController::class, 'destroy'])->name('categoriedestroy');
	});

	//zedtou lel formation
	Route::prefix('formation')->group( function(){
		Route::get('formations', [FormationController::class, 'index'])->name('formations');
		Route::get('formationcreate', [FormationController::class, 'create'])->name('formationcreate');
		Route::post('store', [FormationController::class, 'store'])->name('formationstore');
		Route::get('/{id}/edit', [FormationController::class, 'edit'])->name('formationedit');
    	Route::put('/{id}', [FormationController::class, 'update'])->name('formationupdate');
    	Route::delete('/{id}', [FormationController::class, 'destroy'])->name('formationdestroy');
		Route::get('/{id}', [FormationController::class, 'show'])->name('formationshow');


	});

	Route::get('/admin/formations/filter', 'FormationController@filter')->name('formations.filter');

	Route::get('/panier', [App\Http\Controllers\PanierController::class, 'index'])->name('panier.index');

	// Route::post('/panier/supprimer', 'PanierController@supprimer')->name('panier.supprimer');

	Route::post('/panier/supprimer', [PanierController::class, 'supprimer'])->name('panier.supprimer');
	// Route::post('/panier/ajouter', [PanierController::class, 'panier.ajouter']);
	Route::post('/panier/ajouter', [App\Http\Controllers\PanierController::class, 'ajouter'])->name('panier.ajouter');
	Route::get('/panier/items', 'PanierController@getItems');
	Route::get('/panier/count', [PanierController::class, 'getItemsCount']);
	Route::get('/panier/items', [PanierController::class, 'getCartItems']);

	// Route::get('/panier/check-in-cart/{formationId}', [PanierController::class, 'checkInCart']);
	Route::get('/panier/check-in-cart/{formationId}', [PanierController::class, 'checkInCart'])->name('panier.check-in-cart');

	// Route::get('/panier/check/{formationId}', [PanierController::class,'checkInCart'])->name('panier.check');
	// Route::get('/panier/items-count', [PanierController::class, 'getItemsCount'])->name('panier.items-count');
	Route::get('/panier/items-count', [PanierController::class, 'getItemsCount']);
	// Route::post('/panier/check', [PanierController::class, 'check']);
	// Route::post('/reservation/create', 'ReservationController@store')->name('api.reservation.create');
	// Route::post('/api/reservations/create', 'App\Http\Controllers\ReservationController@create');
	Route::post('/api/reservations/create', [ReservationController::class, 'create']);
    Route::get('/api/user/check-auth', [ReservationController::class, 'checkAuth']);
    Route::get('/cart/details', 'App\Http\Controllers\CartController@getCartDetails');
    Route::get('/api/reservations/check', [ReservationController::class, 'checkReservation']);

// Annuler une réservation existante
Route::post('/api/reservations/cancel', [ReservationController::class, 'cancelReservation']);

	// Route::post('/panier/reserver', [App\Http\Controllers\ReservationController::class, 'reserver'])->middleware('auth');
	// Route::post('/reservations/create', [App\Http\Controllers\ReservationController::class, 'create']);
	// Route::post('/reservation/create-from-cart', 'ReservationController@createFromCart')->name('reservation.createFromCart');

	// Route optionnelle pour afficher les réservations de l'utilisateur
	Route::prefix('cours')->group(function () {
        Route::get('cours', [CoursController::class, 'index'])->name('cours');
        Route::get('/courscreate', [CoursController::class, 'create'])->name('courscreate');
        Route::post('store', [CoursController::class, 'store'])->name('coursstore');
        Route::get('/{id}', [CoursController::class, 'show'])->name('coursshow');
        Route::get('/{id}/edit', [CoursController::class, 'edit'])->name('coursedit');
        Route::put('/{id}', [CoursController::class, 'update'])->name('coursupdate');
        Route::delete('/{id}', [CoursController::class, 'destroy'])->name('coursdestroy');

    });

	Route::prefix('chapitres')->group(function () {
        Route::get('chapitres', [ChapitreController::class, 'index'])->name('chapitres');
		// Route::get('chapitrecreate', [ChapitreController::class, 'create'])->name('chapitrecreate');

        Route::get('chapitrecreate/{cours_id?}', [ChapitreController::class, 'create'])->name('chapitrecreate');
        Route::post('store', [ChapitreController::class, 'store'])->name('chapitrestore');
        Route::get('{id}', [ChapitreController::class, 'show'])->name('chapitreshow');
        Route::get('{id}/edit', [ChapitreController::class, 'edit'])->name('chapitreedit');
        Route::put('{id}', [ChapitreController::class, 'update'])->name('chapitreupdate');
        Route::delete('{id}', [ChapitreController::class, 'destroy'])->name('chapitredestroy');
    });



    Route::prefix('lessons')->group(function () {
        Route::get('lessons', [LessonController::class, 'index'])->name('lessons');
        Route::get('lessoncreate', [LessonController::class, 'create'])->name('lessoncreate');
        Route::post('store', [LessonController::class, 'store'])->name('lessonstore');
        Route::get('/{lesson}', [LessonController::class, 'show'])->name('lessonshow');
        Route::get('/{lesson}/edit', [LessonController::class, 'edit'])->name('lessonedit');
        Route::put('/{lesson}', [LessonController::class, 'update'])->name('lessonupdate');
        Route::delete('/{lesson}', [LessonController::class, 'destroy'])->name('lessondestroy');

    });


	Route::get('/get-file', [LessonController::class, 'getFile'])->name('get.file');
	Route::post('/upload-temp', [LessonController::class, 'uploadTemp'])->name('upload.temp');
	Route::post('/delete-temp', [LessonController::class, 'deleteTemp'])->name('delete.temp');
	Route::post('/delete-file', [LessonController::class, 'deleteFile'])->name('delete.file');
	Route::post('/deletefile', [LessonController::class, 'deleteFile'])->name('deletefile');
	Route::delete('/admin/lesson/delete-file/{lessonId}', [LessonController::class, 'file.delete']);



	// Routes pour la gestion des fichiers
// Route::post('/upload/temp', [App\Http\Controllers\FilePreviewController::class, 'uploadTemp'])->name('upload.temp');
// Route::post('/delete/temp', [App\Http\Controllers\FilePreviewController::class, 'deleteTemp'])->name('delete.temp');
// Route::get('/file/preview', [App\Http\Controllers\FilePreviewController::class, 'preview'])->name('file.preview');






	//zedtou tw
	Route::delete('/admin/lesson/delete-file/{fileId}', 'App\Http\Controllers\LessonController@deleteFile')->name('lesson.deleteFile');

	// Route::post('/convert-pptx-to-pdf', [LessonController::class, 'convertPptxToPdf']);

	Route::get('/api/preview/docx', 'App\Http\Controllers\LessonControllerr@previewDocx');
	Route::get('/api/preview/zip', 'App\Http\Controllers\LessonControllerr@previewZip');







	Route::prefix('feedbacks')->group(function () {
		Route::get('/', [FeedbackController::class, 'index'])->name('feedbacks');
		Route::get('/feedbackcreate', [FeedbackController::class, 'create'])->name('feedbackcreate');
		Route::post('/', [FeedbackController::class, 'store'])->name('feedbackstore');
		Route::get('/{id}', [FeedbackController::class, 'show'])->name('feedbackshow');
		Route::get('/{id}/edit', [FeedbackController::class, 'edit'])->name('feedbackedit');
		Route::put('/{id}', [FeedbackController::class, 'update'])->name('feedbackupdate');
		Route::delete('/{id}', [FeedbackController::class, 'destroy'])->name('feedbackdestroy');
		Route::post('/deleteSelected', [FeedbackController::class, 'deleteSelected'])->name('deleteSelected');

	});

});
//route pour les gestions des roles et des permissions
Route::middleware(['auth', 'role:admin|super-admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}',[UserController::class,'destroy'])->name('users.destroy');
    Route::get('/users/{user}',[UserController::class,'show'])->name('users.roles');
    Route::delete('/users/{user}/roles/{role}',[UserController::class,'removeRole'])->name('users.roles.remove');
    Route::delete('/users/{user}/permissions/{permission}',[UserController::class,'revokePermission'])->name('users.permissions.revoke');
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

});



