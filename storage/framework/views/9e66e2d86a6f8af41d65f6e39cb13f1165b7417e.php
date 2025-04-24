<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      color: #333;
      background-color: #f9f9f9;
      padding: 20px;
    }

    .password-container {
      max-width: 800px;
      margin: 0 auto;
      background: white;
      border-radius: 4px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      padding: 30px;
    }

    .form-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
      font-size: 18px;
      color: #333;
    }

    .form-header i {
      color: #333;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .mt-5 {
      margin-top: 30px;
    }

    .text-center {
      text-align: center;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: normal;
      font-size: 16px;
      color: #333;
    }

    .input-wrapper {
      position: relative;
    }

    input[type="password"] {
      width: 100%;
      padding: 10px 10px 10px 35px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 16px;
    }

    .icon {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #777;
      font-size: 16px;
    }

    .buttons-container {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
      gap: 15px;
    }

    .btn-save {
        background-color: #4361ee;
        color: white !important;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 4px;
        font-weight: 500;
        font-size: 16px;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }

    .btn-save:hover {
        background-color: #3951d0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    /* .btn i {
      font-size: 14px;
      color: white !important;
    } */



  </style>
</head>
<body>
  <div class="password-container">
    <div class="form-card">
      <div class="form-header">
        <i class="fas fa-lock"></i>
        <span>Modification du mot de passe</span>
      </div>

      <form id="edit-password-form" class="ajax-form" action="<?php echo e(route('profile.updatePassword')); ?>" method="POST" data-reload-tab="account">
        <?php echo csrf_field(); ?>
        <div class="form-group">
          <label for="old_password">Ancien mot de passe</label>
          <div class="input-wrapper">
            <i class="fas fa-key icon"></i>
            <input type="password" id="old_password" name="old_password" required>
          </div>
        </div>

        <div class="form-group">
          <label for="password">Nouveau mot de passe</label>
          <div class="input-wrapper">
            <i class="fas fa-lock icon"></i>
            <input type="password" id="password" name="password" required>
          </div>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Confirmation du mot de passe</label>
          <div class="input-wrapper">
            <i class="fas fa-check-circle icon"></i>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
          </div>
        </div>

        <div class="buttons-container">
            <button class="btn btn-outline-secondary back-btn" type="button" data-back-tab="account">
                <i class="fa fa-arrow-left"></i> Retour
            </button>
          <button type="submit" class="btn btn-save">
            <i class="fas fa-save"></i> Enregistrer
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
<?php /**PATH D:\apprendre laravel\platformeEls\resources\views/admin/apps/profile/editPassword.blade.php ENDPATH**/ ?>