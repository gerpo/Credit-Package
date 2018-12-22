<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Codes</title>
</head>
<body>
<table style="width: 100%; border-collapse: collapse;">
    <tbody>
        <tr>
        @foreach($codes as $code)
        @if($loop->index % 3 === 0 && $loop->index > 0)
        </tr><tr>
        @endif

            <td style="text-align: center; border: 2px solid black; padding: 20px 10px; font-size: 20px">
                <p style="margin: 0;">{{ $code->value }} Credits</p>
                <p style="margin: 0;">Code: <span style="font-family: monospace">{{ $code->code }}</span></p>
            </td>
        @if($loop->last && count($codes) % 3 !== 0)
            @for($i= 0; $i < (3-count($codes) % 3); $i++)
                    <td></td>
            @endfor
        @endif

        @endforeach
    </tbody>
</table>
</body>
</html>