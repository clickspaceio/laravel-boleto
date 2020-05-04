<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Boleto Bancário - {{ $sacador_avalista['nome'] }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('css/pure-min.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/17f9410664.js" crossorigin="anonymous"></script>

    <style type="text/css">
        {!! $css !!}
    </style>
</head>
<body>

@yield('boleto')

<script src="{{ asset('js/clipboard.min.js') }}"></script>

<script type="text/javascript">
    function printBankSlip() {
        window.print();
    }
    var clipboard = new ClipboardJS('.copy-digitable-line');
    clipboard.on('success', function(e) {
        document.getElementById("btnCopyDigitableLine").style.display = 'none';
        document.getElementById("btnCopyDigitableLineSelected").style.display = 'inline-block';
        setTimeout( function() {
            document.getElementById("btnCopyDigitableLine").style.display = 'inline-block';
            document.getElementById("btnCopyDigitableLineSelected").style.display = 'none';
        }, 5000 );
    });
    @if(isset($imprimir_carregamento) && $imprimir_carregamento === true)
        window.onload = function () {
            printBankSlip();
        }
    @endif
</script>

</body>
</html>