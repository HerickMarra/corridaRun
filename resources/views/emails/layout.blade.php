<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notificação - Sisters Esportes' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f9fafb;
            padding: 40px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #0052FF;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: -1px;
            font-style: italic;
        }

        .content {
            padding: 40px;
            color: #374151;
            line-height: 1.6;
            font-size: 16px;
        }

        .content h2 {
            color: #111827;
            font-size: 20px;
            font-weight: 700;
            margin-top: 0;
        }

        .footer {
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            background-color: #f3f4f6;
        }

        .button {
            display: inline-block;
            background-color: #0052FF;
            color: #ffffff !important;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 20px;
        }

        .footer-links {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: #0052FF;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 600;
        }

        hr {
            border: 0;
            border-top: 1px solid #e5e7eb;
            margin: 30px 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>Sisters Esportes</h1>
            </div>
            <div class="content">
                {!! $slot !!}
            </div>
            <div class="footer">
                <div class="footer-links">
                    <a href="{{ config('app.url') }}">Site Oficial</a>
                    <a href="{{ config('app.url') }}/minhas-inscricoes">Minhas Inscrições</a>
                </div>
                <p>&copy; {{ date('Y') }} Sisters Esportes. Todos os direitos reservados.</p>
                <p style="font-size: 10px;">Você recebeu este e-mail porque está cadastrado em nossa plataforma.</p>
            </div>
        </div>
    </div>
</body>

</html>