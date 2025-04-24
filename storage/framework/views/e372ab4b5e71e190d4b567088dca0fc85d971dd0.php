
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Activation de votre compte ELS</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #000000; max-width: 600px; margin: 0 auto; padding: 20px; }
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
        <h2 style="color: #2c3e50;">Bienvenue sur ELS, <?php echo e($user->lastname); ?> !</h2>

        <p>Votre compte  a été créé avec succès sur la plateforme ELS.</p>

        <div class="credentials">
            <p><strong>🔐 Vos identifiants de connexion :</strong></p>
            <p><strong>Email :</strong> <?php echo e($user->email); ?></p>
            <p><strong>Mot de passe temporaire :</strong> <?php echo e($password); ?></p>
        </div>

        <p>Pour activer votre compte, vous devrez saisir le code de validation suivant lors de votre première connexion :</p>

        <div class="code"><?php echo e($code); ?></div>


        <p>Pour des raisons de sécurité, vous serez invité à :</p>
        <ol>
            <li>Valider votre compte avec le code ci-dessus</li>
            <li>Changer votre mot de passe temporaire</li>
        </ol>

        <p><a href="<?php echo e(url('/login')); ?>">Se connecter</a></p>

        <p>Si vous n'avez pas demandé la création de ce compte, veuillez contacter immédiatement le support à <a href="mailto:els.center2022@gmail.com">els.center2022@gmail.com</a>.</p>
    </div>

    <div class="footer">
        <p>© <?php echo e(date('Y')); ?> Plateforme ELS - Tous droits réservés</p>
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>
<?php /**PATH D:\apprendre laravel\platformeEls\resources\views/emails/user_created.blade.php ENDPATH**/ ?>