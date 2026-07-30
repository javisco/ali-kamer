<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Réinitialisation du mot de passe</title>

</head>

<body style="margin:0;padding:0;background:#f3f4f6;">

    <table width="100%" cellpadding="0" cellspacing="0">

        <tr>

            <td align="center" style="padding:40px;">

                <table width="650" cellpadding="0" cellspacing="0"
                    style="background:white;border-radius:12px;overflow:hidden;">

                    <tr>

                        <td style="background:#2563eb;padding:35px;text-align:center;color:white;">

                            <h1 style="margin:0;font-size:32px;">
                                ALI-KAMER
                            </h1>

                            <p style="margin-top:10px;font-size:16px;">
                                Réinitialisation de votre mot de passe
                            </p>

                        </td>

                    </tr>

                    <tr>

                        <td style="padding:40px;">

                            <p>

                                Bonjour

                                <strong>

                                    {{ $user->name }}

                                </strong>,

                            </p>

                            <p>

                                Nous avons reçu une demande de réinitialisation de votre mot de passe.

                            </p>

                            <p>

                                Si vous êtes bien à l'origine de cette demande,
                                cliquez sur le bouton ci-dessous.

                            </p>

                            <p style="text-align:center;margin:40px 0;">

                                <a href="{{ $url }}"
                                    style="
display:inline-block;
padding:16px 35px;
background:#2563eb;
color:white;
text-decoration:none;
border-radius:8px;
font-weight:bold;">

                                    Réinitialiser mon mot de passe

                                </a>

                            </p>

                            <p>

                                Ce lien est valable pendant

                                <strong>

                                    60 minutes.

                                </strong>

                            </p>

                            <p>

                                Si vous n'avez pas demandé cette opération,
                                ignorez simplement cet e-mail.

                            </p>

                            <hr>

                            <p style="font-size:13px;color:#777;">

                                Si le bouton ne fonctionne pas,
                                copiez ce lien dans votre navigateur :

                            </p>

                            <p style="word-break:break-all;">

                                {{ $url }}

                            </p>

                        </td>

                    </tr>

                    <tr>

                        <td style="
background:#f3f4f6;
padding:20px;
text-align:center;
font-size:13px;
color:#666;">

                            © {{ date('Y') }}

                            ALI-KAMER

                        </td>

                    </tr>

                </table>

            </td>

        </tr>

    </table>

</body>

</html>
