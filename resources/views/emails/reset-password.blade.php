<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
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
        <h2 style="color: #2c3e50;">Réinitialisation de votre mot de passe</h2>
        <p>Bonjour <strong>{{ $lastname }}</strong>,</p>
        <p>Vous avez demandé à réinitialiser votre mot de passe. Veuillez utiliser le code ci-dessous :</p>
        <div class="code">{{ $code }}</div>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} - Tous droits réservés</p>
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>
