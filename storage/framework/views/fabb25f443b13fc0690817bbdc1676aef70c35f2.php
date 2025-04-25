<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de votre compte</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #2c3e50; padding: 20px; text-align: center; }
        .header img { max-width: 150px; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #777; background-color: #ecf0f1; }
        .button { display: inline-block; padding: 10px 20px; background-color: #3498db; color: white; text-decoration: none; border-radius: 4px; margin: 15px 0; }
        .credentials { background-color: #e8f4fc; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0; }
        .code { font-size: 24px; letter-spacing: 3px; color: #0a0606; font-weight: bold; text-align: center; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
    </div>
    <div class="content">
        <h2 style="color: #2c3e50;">Bienvenue <?php echo e($user->lastname); ?></h2>
        <p>Merci de vous être inscrit(e) sur la plateforme ELS.</p>
        <p>Pour finaliser votre inscription, veuillez utiliser le code de validation ci-dessous :</p>
        <div class="code">Code de validation :<?php echo e($user->validation_code); ?></div>
        <p>Veuillez saisir ce code dans le formulaire prévu à cet effet afin d'activer votre compte.</p>
    </div>
    <div class="footer">
        <p>© <?php echo e(date('Y')); ?> - Tous droits réservés</p>
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>
<?php /**PATH C:\Users\hibah\PFE\PlateformeELS\resources\views/emails/account_validation.blade.php ENDPATH**/ ?>