<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новая заявка с сайта</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .info-block {
            margin-bottom: 25px;
            border-left: 4px solid #667eea;
            padding-left: 15px;
        }
        .info-block h3 {
            margin: 0 0 10px 0;
            color: #667eea;
            font-size: 18px;
        }
        .info-block p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .comment-block {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
            border: 1px solid #e9ecef;
        }
        .comment-block h3 {
            margin: 0 0 10px 0;
            color: #667eea;
            font-size: 18px;
        }
        .comment-block p {
            margin: 0;
            white-space: pre-wrap;
            line-height: 1.6;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #e9ecef;
        }
        .label {
            font-weight: bold;
            color: #667eea;
        }
        hr {
            border: none;
            border-top: 1px solid #e9ecef;
            margin: 20px 0;
        }
        .date {
            font-size: 12px;
            color: #999;
            text-align: right;
            margin-top: 20px;
        }
        .phone-number {
            color: #28a745;
            font-weight: bold;
            font-size: 18px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                width: auto;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Новая заявка с сайта</h1>
        </div>
        
        <div class="content">
            <div class="info-block">
                <h3>👤 Информация о клиенте</h3>
                <p><span class="label">Имя:</span> {{ $name }}</p>
                <p><span class="label">Телефон:</span> <span class="phone-number">{{ $phone }}</span></p>
            </div>
            
            @if($comment != 'Не указан')
                <div class="comment-block">
                    <h3>💬 Комментарий клиента</h3>
                    <p>{{ $comment }}</p>
                </div>
            @endif
            
            <hr>
            
            <div class="date">
                <span class="label">Дата и время заявки:</span> {{ $date }}
            </div>
        </div>
        
        <div class="footer">
            <p>Это письмо было отправлено автоматически. Пожалуйста, не отвечайте на него.</p>
            <p>Для связи с клиентом используйте указанные выше контактные данные.</p>
        </div>
    </div>
</body>
</html>