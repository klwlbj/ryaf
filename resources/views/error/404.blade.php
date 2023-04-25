<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $statusCode }} - Error</title>
        <style>
            .error {
                margin: 40px auto;
                padding: 40px 24px;
                width: 1024px;
                box-sizing: border-box;
                border-radius: 4px;
                background-color: #fff;
            }

            .error-header {
                margin-bottom: 40px;
                font-size: 20px;
                font-weight: 500;
            }

            .error-empty {
                height: 606px;
            }

            .empty {
                margin-top: 152px;
                text-align: center;
                font-size: 18px;
                color: #999;
            }

            .empty-img {
                margin: 0 auto 20px;
                height: 244px;
                width: 222px;
                background-image: url("{{asset(getStaticPath('www', 'www-assets/dist/images/error.png'))}}");
                background-repeat: no-repeat;
                background-position: center center;
                /* background-size: cover; */
            }
        </style>
    </head>
    <body>
       <div>
            <section class="error error-empty">
                <div class="empty">
                    <div class="empty-img"></div>
                    <p>{{ $msg ?? "该页面不存在" }}</p>
                </div>
            </section>
       </div>
    </body>
</html>

