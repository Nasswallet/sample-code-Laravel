<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NASSWALLET PAYMENT</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">

    <!-- Styles -->
</head>

<body>


    @if(!empty($loginURL))
        <script type="text/javascript">
            window.open(`{{$loginURL}}` ,"_blank");
        </script>
    @endif


</body>

</html>
