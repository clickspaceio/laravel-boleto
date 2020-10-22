@extends('BoletoHtmlRender::layout')
@section('boleto')

    @foreach($boletos as $i => $boleto)
        @php extract($boleto, EXTR_OVERWRITE); @endphp
        @if($mostrar_instrucoes)
            <div class="noprint info content">
                <div class="botoes">
                    <a href="#imprimir" onclick="printBankSlip(); return false" class="pure-button button-xlarge pure-button-primary">
                        <i class="fas fa-print"></i>
                        Imprimir boleto
                    </a>

                    <a href="{{ request()->url() }}.pdf" class="pure-button button-xlarge">
                        <i class="far fa-file-pdf"></i>
                        Salvar em PDF
                    </a>
                </div>
                <h1 class="header_titulo">Seu boleto foi gerado com sucesso :)</h1>
                <div class="header_subtitulo">Pague no internet banking, app do banco, caixas eletrônicos, lotéricas ou agências bancárias.</div>

                <div class="instrucoes_linhadigitavel">&#11015; Seu código para pagamento &#11015;</div>
                <div class="digitable-line copy-digitable-line" data-clipboard-text="{{ preg_replace('/\D/', '', $linha_digitavel) }}">
                    <div class="barcode">{!! $codigo_barras !!}</div>
                    <span id="spanDigitableLine" class="text">{{ $linha_digitavel }}</span>
                </div>
                <div class="actions-digitable-line">
                    <a href="#" id="btnCopyDigitableLine" class="pure-button copy-digitable-line" data-clipboard-text="{{ preg_replace('/\D/', '', $linha_digitavel) }}">
                        <i class="far fa-copy"></i>
                        copiar código
                    </a>
                    <a href="#" id="btnCopyDigitableLineSelected" class="pure-button button-success" style="display: none">
                        <i class="fas fa-check"></i>
                        código copiado
                    </a>
                </div>
                <br>

                <div class="linha-cinza" style="margin-bottom: 20px;"></div>
            </div>
        @endif

        <div class="boleto-container">
            <div class="boleto">
                <div class="info-empresa">
                    @if ($logo)
                        <div style="display: inline-block;">
                            <img alt="logo" src="{{ $logo_base64 }}"/>
                        </div>
                    @endif
                </div>
                <br>

                <table class="table-boleto" cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                    <tr>
                        <td colspan="3" class="top-2">
                            <div class="titulo">Beneficiário</div>
                            <div class="conteudo">{{ $beneficiario['nome'] }}</div>
                        </td>
                        <td class="top-2">
                            <div class="titulo">Vencimento</div>
                            <div class="conteudo rtl">{{ $data_vencimento->format('d/m/Y') }}</div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="titulo">Data do Documento</div>
                            <div class="conteudo">{{ $data_documento->format('d/m/Y') }}</div>
                        </td>
                        <td>
                            <div class="titulo">No. do Documento</div>
                            <div class="conteudo">{{ $numero_documento }}</div>
                        </td>
                        <td>
                            <div class="titulo">(=) Valor do Documento</div>
                            <div class="conteudo">R$ {{ $valor }}</div>
                        </td>
                        <td>
                            <div class="titulo">(=) Valor Cobrado</div>
                            <div class="conteudo rtl"></div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <div class="titulo">Demonstrativo</div>
                            <div class="conteudo">{{ $demonstrativo[0] }}</div>
                            <div class="conteudo">{{ $demonstrativo[1] }}</div>
                            <div class="conteudo">{{ $demonstrativo[2] }}</div>
                            <div class="conteudo">{{ $demonstrativo[3] }}</div>
                            <div style="margin-bottom: 5px;" class="conteudo">{{ $demonstrativo[4] }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="titulo">Pagador</div>
                            <div class="conteudo">{{ $pagador['nome_documento'] }}</div>
                            <div class="conteudo">{{ $pagador['endereco'] }}</div>
                            <div class="conteudo">{{ $pagador['endereco2'] }}</div>

                        </td>
                        <td class="noleftborder">
                            {{--<div class="titulo" style="margin-top: 10px; margin-right: 10px; text-align: right">--}}
                                {{--<a href="https://www.pagclick.com.br" target="_blank">--}}
                                    {{--<img src="data:image/png;base64,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" alt="Pagclick" />--}}
                                {{--</a>--}}
                            {{--</div>--}}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" class="noleftborder">
                            <div class="titulo">Sacador/Avalista
                                <div class="conteudo">{{ $sacador_avalista ? $sacador_avalista['nome_documento'] : '' }}</div>
                                <div class="conteudo">{{ $sacador_avalista ? $sacador_avalista['endereco'] : '' }}</div>
                                <div class="conteudo">{{ $sacador_avalista ? $sacador_avalista['endereco2'] : '' }}</div>

                            </div>
                        </td>
                        <td colspan="2" class="norightborder noleftborder" style="vertical-align: top;">
                            <div class="conteudo noborder rtl" style="font-weight: normal">Autenticação mecânica - Recibo do Pagador</div>
                        </td>
                    </tr>

                    </tbody>
                </table>
                <div class="linha-pontilhada"></div>
                <br>

                <!-- Ficha de compensação -->
                @include('BoletoHtmlRender::partials/ficha-compensacao')

                @if(count($boletos) > 1 && count($boletos)-1 != $i)
                    <div style="page-break-before:always"></div>
                @endif

            </div>
        </div>
    @endforeach
@endsection